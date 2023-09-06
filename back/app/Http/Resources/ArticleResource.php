<?php

namespace App\Http\Resources;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            "libelle" => $this->libelle,
            "id" => $this->id,
            "prix" => $this->prix,
            "stock" => $this->stock,
            // "categorie" => new CategorieResource(Categorie::where("id", $this->categorie_id)->first()),
            "categorie" => new CategorieResource($this->categorie),
            "reference" => $this->reference
        ];
    }
}
