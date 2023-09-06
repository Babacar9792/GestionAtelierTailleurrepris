<?php

namespace App\Http\Controllers;

use App\Http\Requests\FournisseurRequest;
use App\Http\Resources\FournisseurResource;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return FournisseurResource::collection(Fournisseur::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FournisseurRequest $request)
    {
        try {
            $fournisseur = new Fournisseur();
            $fournisseur->nom_fournisseur = ucfirst(strtolower($request->fournisseur));
            $fournisseur->save();
            return ["message" => '', "status" => true, "data" => [new FournisseurResource($fournisseur)]];
        } catch (\Throwable $th) {
            return ["message" => $th->getMessage(), "status" => false, "data" => []];
            //throw $th;
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return [
            "message" => "",
            "data" => FournisseurResource::collection(Fournisseur::where("nom_libelle", "like", "%" . $request->fournisseur . "%")->get()),
            "statut" => 200
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        //
    }
}
