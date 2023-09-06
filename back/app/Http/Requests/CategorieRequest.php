<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CategorieRequest extends FormRequest
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
            "libelle" => "required|min:3|string|unique:categories,libelle"
            //
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

    // ... autres méthodes de validation ...


    public function messages()
    {
        return [
            "libelle.required" => "Le libelle doit contenir au moins trois caractères",
            "libelle.min" => "Le libelle doit contenir au moins trois caractères",
            "libelle.unique" => "Ce libelle existe déjà",
            "libelle.string" => "Le libelle ne doit être constitué que par des lettres"
        ];
    }
}
