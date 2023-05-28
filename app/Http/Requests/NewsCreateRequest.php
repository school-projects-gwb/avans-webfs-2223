<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:75|string',
            'content' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Titel is verplicht.',
            'title.max' => 'Title mag niet langer zijn dan :max karakters.',
            'title.string' => 'Titel moet uit tekst bestaan.',
            'content.required' => 'Inhoud is verplicht.',
            'content.string' => 'Inhoud moet uit tekst bestaan.',
        ];
    }
}
