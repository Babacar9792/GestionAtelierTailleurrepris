<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FournisseurRequest extends FormRequest
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
            "fournisseur" => "required|unique:fournisseurs,nom_fournisseur"
            //
        ];
    }

    public function messages() : array
    {
        return [
            "fournisseur.required" => "Le nom du fournisseur est obligatoire",
            "fournisseur.unique" => "Ce fournisseur est déjà enregistré"
        ];

    }
}
