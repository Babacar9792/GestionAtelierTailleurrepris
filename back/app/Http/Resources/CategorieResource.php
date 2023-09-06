<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Models\ArticleVente;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->whenNotNull($this->id),
            "libelle" => $this->libelle,
            "type_categorie" => $this->type_categorie,
            "enregistrement_categorie" =>  $this->type_categorie=== "AC" ? count(Article::where("categorie_id", $this->id)->get()) : count(ArticleVente::where("categorie_id", $this->id)->get())
        ];
    }
}
