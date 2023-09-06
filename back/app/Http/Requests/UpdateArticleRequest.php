<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateArticleRequest extends FormRequest
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
            "libelle" => "sometimes|string|min:3|unique:articles,libelle",
            "prix" => "sometimes|numeric",
            "stock" => "sometimes|numeric",
            "categorie" => "sometimes|exists: categories, id",
            "reference" => "sometimes",
            "photo" => "sometimes"
            //
        ];
    }

    public function messages(): array
    {
        return [
            "libelle.string" => "Le libelle ne doit être constitué que par des lettres",
            "libelle.min" => "Le libelle doit comporter au oins trois caractères",
            "libelle.unique" => "Le libelle que vous avez choisi existe déjà",
            "prix.numeric" => "Le prix doit être un nombre strictement positif",
            "stock.numeric" => "Le stock doit être nombre strictement positf",
            "categorie.exists" => "La categorie choisie n'existe pas",
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
