<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleVenteRequest extends FormRequest
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
            "libelle" => "required|string|min:3|unique:article_ventes,libelle",
            "categorie" => "required",
            "promo" => "sometimes|numeric",
            "marge" => "required|numeric",
            "prix_confection" => "required|numeric|min:0",
            "prix_vente" => "required|numeric|min:0",
            "photo" => "sometimes",
            "quantite_stock" => "sometimes|numeric|min:0",
            "reference" => "required|string"
            //
        ];
    }



    public function messages()
    {
        return [
            "libelle.required" => "Le libelle de l'article de vente est obligatoire et doit comporter trois caracteres",
            "libelle.string" => "Le libelle ne doit comporter que des lettres",
            "libelle.min" => "Le libelle doit comporter au moins ttrois caracteres",
            "libelle.unique" => "Ce libelle existe déjà ",
            "categorie.required" => "Vous devez choisir une categorie",
            // "categorie.exists" => "L'id que vous avez choisi n'existe pas ou a été supprimer",
            "promo" => "La valeur du promo doit etre numeric",
            "marge.required" => "La marge doit etre definie pour que le prix de vente puisse etre calculé",
            "prix_confection.required" => "Prix de confection ne doit pas etre vide",
            "prix_confection.numeric" => "le prix de confection est obligatoire",
            "prix_confection.min" => "Le prix de confection doit etre strictement positif",
            "prix_vente.required" => "Le prix de vente est obligatoire",
            "prix_vente.min" => "Le prix de vente doit etre strictement positif",
            // "quantite_stock.required" => "Le prix de vente est obligatoire",
            "quantite_stock.min" => "Le prix de vente doit etre strictement positif",
            "reference.required" => "La reference est obligatoire"
            
        ];
    }
}
