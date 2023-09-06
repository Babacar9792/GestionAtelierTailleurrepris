<?php

namespace App\Http\Resources;

use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "data" => $this->collection,
            "categories" => CategorieResource::collection(Categorie::where("type_categorie","AC")->get()),
            "fournisseurs" => FournisseurResource::collection(Fournisseur::all()),
            "message" => '',
            "status" => true
        ];
    }

    public function paginationInformation($request,$paginated,$default) : array
    {
        return ["links" => $default['meta']["links"]];
        // dd($default)['meta']['links'];
    }
}
