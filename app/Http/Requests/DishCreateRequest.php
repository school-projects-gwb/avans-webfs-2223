<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DishCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:75|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|between:0,99.99',
            'is_discount' => 'nullable|boolean',
            'option_required' => 'nullable|boolean',
            'option_ids' => [
                'nullable',
                'array',
                Rule::exists('options', 'id'),
            ],
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Naam is verplicht.',
            'name.max' => 'Naam mag niet langer zijn dan :max karakters.',
            'name.string' => 'Naam moet uit tekst bestaan.',

            'description.max' => 'Omschrijving mag niet langer zijn dan :max karakters.',
            'description.string' => 'Omschrijving moet uit tekst bestaan.',

            'price.required' => 'Prijs is verplicht.',
            'price.price' => 'Prijs moet een getal zijn.',

            'is_discount.boolean' => 'Korting moet ja/nee zijn.',

            'option_required.boolean' => 'Extra\'s moet ja/nee zijn.',

            'option_ids.array' => 'Opties zijn niet valide.',

            'category_id.required' => 'Categorie is verplicht.',
            'category_id.integer' => 'Categorie is niet valide.',
            'category_id.exists' => 'Categorie is niet valide.',
        ];
    }
}
