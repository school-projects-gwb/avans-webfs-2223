<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesGetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'start_date.required' => 'Begindatum is niet ingevuld!',

            'end_date.required' => 'Einddatum is niet ingevuld!',
            'end_date.after_or_equal' => 'Einddatum moet op of na begindatum komen!',
        ];
    }
}
