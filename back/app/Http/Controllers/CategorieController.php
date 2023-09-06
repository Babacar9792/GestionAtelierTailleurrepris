<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategorieRequest;
use App\Http\Resources\CategorieCollection;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     * Methode pour lister les catégorie par page
     */

    public function index()
    {
        $categorieByPage = request()->query("limit", 4);
        $categories = Categorie::paginate($categorieByPage);
        return  new CategorieCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategorieRequest $request)
    {
        try {
            $categorie = new Categorie();
            $categorie->libelle = $request->libelle;
            $categorie->type_categorie = $request->type_categorie;
            $categorie->save();
            return ["message" => "Insertion reussi", "status" => true, "data" => new CategorieResource($categorie)];
        } catch (\Throwable $th) {
            //throw $th;
            return ['message' => $th->getMessage(), "status" => false, "data" => []];
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        if(!$request->categorie)
        {
            return ["message" => "non vide", "status" => false, "data" => []];
        }
        $categorie = Categorie::where("libelle", $request->categorie)->get();
        if(!$categorie)
        {
            return ["message" => "vide", "status" => true, "data" => []];
        }
        return ["message" => '', "status" => true, "data" => CategorieResource::collection($categorie)];

        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategorieRequest $request)
    {
        //
        $categorie_id = $request->id;
        try {
            Categorie::where("id", $categorie_id)->update(['libelle' => $request->libelle]);
            return ["message" => "La valeur du libelle a été mise à jour", "status" => true, "data" => []];
        } catch (\Throwable $th) {
            return ["message" => $th->getMessage(), "status" => false, "data" => []];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        return $request["ids"];
        Categorie::whereIn("id", $request->idTodeletes)->delete();
        //
    }

    
    public function restore()
    {
        
    }
}
