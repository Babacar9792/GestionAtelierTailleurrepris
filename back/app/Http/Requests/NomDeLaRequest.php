<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NomDeLaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "colonne" => 'required|numeric'
            //
        ];
    }
    public function messages()
    {
        return [
            "colonne.required" => "la valeur de la colonne ne doit pas etre vide",
            "colonne.numeric" => "la valeur de la colonne ne doit comporter que des nombres"
        ];
    }
}
