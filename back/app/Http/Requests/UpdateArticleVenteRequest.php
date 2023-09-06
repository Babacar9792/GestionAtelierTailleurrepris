<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateArticleVenteRequest extends FormRequest
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
            "libelle" => "sometimes|string|min:3",
            /* |unique:article_ventes,libelle */
            "promo" => "sometimes|numeric",
            "marge" => "sometimes|numeric",
            "prix_confection" => "sometimes|numeric|min:0",
            "prix_vente" => "sometimes|numeric|min:0",
            "photo" => "sometimes|string",
            "quantite_stock" => "sometimes|numeric|min:0",
            "reference" => "sometimes|string"
            //
        ];
    }

    public function messages(): array
    {
        return [
            // "libelle.string" => "Le libelle ne doit être constitué que par des lettres",
            // "libelle.min" => "Le libelle doit comporter au oins trois caractères",
            // "libelle.unique" => "Le libelle que vous avez choisi existe déjà",
            // "prix.numeric" => "Le prix doit être un nombre strictement positif",
            // "stock.numeric" => "Le stock doit être nombre strictement positf",
            // "categorie.exists" => "La categorie choisie n'existe pas",
            "libelle.string" => "Le libelle ne doit comporter que des lettres",
            "libelle.min" => "Le libelle doit comporter au moins ttrois caracteres",
            // "libelle.unique" => "Ce libelle existe déjà ",
            "promo" => "La valeur du promo doit etre numeric",
            "prix_confection.required" => "Prix de confection ne doit pas etre vide",
            "prix_confection.numeric" => "le prix de confection est obligatoire",
            "prix_confection.min" => "Le prix de confection doit etre strictement positif",
            "prix_vente.min" => "Le prix de vente doit etre strictement positif",
            "quantite_stock.min" => "Le prix de vente doit etre strictement positif",
            
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'data' => []
                // 'errors' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
