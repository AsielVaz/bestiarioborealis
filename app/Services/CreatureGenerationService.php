<?php

namespace App\Services;

use App\Models\AiGenerationLog;
use App\Models\BestiaryEntry;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CreatureGenerationService
{
    public function __construct(private readonly BestiaryEntryService $entries)
    {
    }

    public function generateFromDescription(string $description, ?int $userId = null): BestiaryEntry
    {
        $provider = config('services.creature_ai.provider', 'deepseek');
        $model = config('services.creature_ai.model', 'deepseek-chat');

        $log = AiGenerationLog::create([
            'user_id' => $userId,
            'provider' => $provider,
            'model' => $model,
            'prompt' => $description,
            'status' => 'pending',
        ]);

        try {
            $json = app()->environment('testing')
                ? $this->fakePayload($description)
                : $this->requestDeepSeekJson($description);

            $payload = $this->validateGeneratedJson($json);
            $entry = $this->entries->createFromJson($payload, $userId);

            $log->update([
                'bestiary_entry_id' => $entry->id,
                'response' => json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'status' => 'success',
            ]);

            return $entry;
        } catch (\Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    public function validateGeneratedJson(array|string $json): array
    {
        $payload = is_string($json) ? json_decode($json, true, flags: JSON_THROW_ON_ERROR) : $json;

        foreach (['title', 'classification', 'threat_level', 'description'] as $required) {
            if (blank($payload[$required] ?? null)) {
                throw new RuntimeException("La respuesta IA no incluye {$required}.");
            }
        }

        return $payload;
    }

    private function requestDeepSeekJson(string $description): array
    {
        $key = config('services.creature_ai.key');
        if (! $key) {
            throw new RuntimeException('Falta DEEPSEEK_API_KEY en el archivo .env.');
        }

        $response = Http::withToken($key)
            ->timeout(60)
            ->post(config('services.creature_ai.endpoint'), [
                'model' => config('services.creature_ai.model', 'deepseek-chat'),
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => 'Devuelve solo JSON válido para una ficha de bestiario con title, classification, threat_level, description, abilities, techniques, weaknesses, loot, stats y scholar_notes.'],
                    ['role' => 'user', 'content' => $description],
                ],
            ])
            ->throw()
            ->json();

        return json_decode($response['choices'][0]['message']['content'] ?? '{}', true, flags: JSON_THROW_ON_ERROR);
    }

    private function fakePayload(string $description): array
    {
        return [
            'title' => 'Criatura generada',
            'classification' => 'Entidad IA',
            'threat_level' => 'Media',
            'description' => $description,
            'theme_key' => 'arcane',
            'abilities' => [['name' => 'Eco sintético', 'description' => 'Repite patrones del prompt con precisión arcana.']],
            'stats' => [['name' => 'Misterio', 'value' => 72, 'value_label' => 'Alto']],
        ];
    }
}
