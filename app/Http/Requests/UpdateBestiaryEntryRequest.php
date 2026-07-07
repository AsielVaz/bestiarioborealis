<?php

namespace App\Http\Requests;

class UpdateBestiaryEntryRequest extends StoreBestiaryEntryRequest
{
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
        $rules = (new StoreBestiaryEntryRequest())->rules();
        $rules['slug'] = ['nullable', 'string', 'max:255', 'unique:bestiary_entries,slug,'.$this->route('entry')?->id];

        return $rules;
    }
}
