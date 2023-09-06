<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            "libelle" => "required|string|min:3|unique:articles,libelle",
            "prix" => "required|numeric",
            "stock" => "required|numeric",
            "categorie_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            "libelle.required" => "Le libelle est obligatoire et doit comporter au moins trois caracteres",
            "prix.required" => "Le prix est obligatoire et doit être supérieur ou égale à 0",
            "stock.required" => "Le stock est obligatorie et doit être supérieur ou égale à 0",
            "libelle.string" => "Le libelle ne doit être constitué que par des lettres",
            "libelle.min" => "Le libelle doit comporter au oins trois caractères",
            "libelle.unique" => "Le libelle que vous avez choisi existe déjà",
            "prix.numeric" => "Le prix doit être un nombre strictement positif",
            "stock.numeric" => "Le stock doit être nombre strictement positf",
            "categorie_id.exist" => "La categorie choisie n'existe pas",
        ];
    }
}
