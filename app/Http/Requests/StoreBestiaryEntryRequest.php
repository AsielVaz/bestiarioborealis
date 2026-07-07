<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBestiaryEntryRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        foreach (['subtitles', 'affinities', 'habitats', 'behaviors'] as $field) {
            if (is_string($this->input($field))) {
                $this->merge([$field => $this->parseStringList($this->input($field))]);
            }
        }

        foreach (['weaknesses' => 'description', 'scholar_notes' => 'note'] as $field => $key) {
            if (is_string($this->input($field))) {
                $this->merge([$field => $this->parseObjectList($this->input($field), $key)]);
            }
        }

        foreach (['abilities', 'techniques', 'loot', 'stats', 'vignettes'] as $field) {
            if (is_string($this->input($field))) {
                $this->merge([$field => $this->parseJsonList($this->input($field))]);
            }
        }
    }

    private function parseStringList(string $value): array
    {
        $value = trim($value);

        if (str_starts_with($value, '[')) {
            return json_decode($value, true) ?: [];
        }

        return collect(preg_split('/\R/', $value))->filter()->values()->all();
    }

    private function parseObjectList(string $value, string $key): array
    {
        $value = trim($value);

        if (str_starts_with($value, '[')) {
            return json_decode($value, true) ?: [];
        }

        return collect(preg_split('/\R/', $value))
            ->filter()
            ->map(fn ($line) => [$key => $line])
            ->values()
            ->all();
    }

    private function parseJsonList(string $value): array
    {
        $value = trim($value);

        return $value === '' ? [] : (json_decode($value, true) ?: []);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:bestiary_entries,slug'],
            'classification' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'threat_level' => ['required', 'string', 'max:255'],
            'height' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'last_record' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:255'],
            'final_combat_scenario' => ['nullable', 'string'],
            'main_image_path' => ['nullable', 'string', 'max:255'],
            'dossier_theme_id' => ['nullable', 'exists:dossier_themes,id'],
            'theme_key' => ['nullable', 'exists:dossier_themes,key'],
            'published_at' => ['nullable', 'date'],
            'origin' => ['nullable', 'array'],
            'subtitles' => ['nullable', 'array', 'max:3'],
            'affinities' => ['nullable', 'array'],
            'habitats' => ['nullable', 'array'],
            'behaviors' => ['nullable', 'array'],
            'abilities' => ['nullable', 'array'],
            'techniques' => ['nullable', 'array'],
            'weaknesses' => ['nullable', 'array'],
            'loot' => ['nullable', 'array'],
            'stats' => ['nullable', 'array'],
            'stats.*.value' => ['nullable', 'integer', 'between:0,100'],
            'vignettes' => ['nullable', 'array'],
            'scholar_notes' => ['nullable', 'array'],
        ];
    }
}
