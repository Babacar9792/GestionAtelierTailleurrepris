<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleFournisseur;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categorie = Categorie::where("type_categorie", "AC")->pluck("id");
        $articleByPage = request()->query("item", 3);
        $articles = Article::whereIn("categorie_id", [...$categorie])->paginate($articleByPage);
        return new ArticleCollection($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        try {
            DB::beginTransaction();
            $article = new Article();
            $article->libelle = ucfirst(strtolower($request->libelle));
            $article->prix = $request->prix;
            $article->stock = $request->stock;
            $article->categorie_id = $request->categorie_id;
            $article->reference = $request->reference;
            $article->photo = $request->photo;
            $article->save();
            $article->fournisseurs()->attach($request->fournisseurs);
            DB::commit();
            return ["message" => "Insertion reussi", "statut" => 200, "data" => [
                "articles" => new ArticleResource($article)
            ]];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ["message" => $th->getMessage(), "statut" => 404, "data" => []];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {   
        return Article::where("libelle", "like", ucfirst(strtolower($request->article))  . "%")->get();
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }
    public function updateArticle(Request $request)
    {
        $articleToUpdate = [];
        $message = '';
        // $recherche = [];
        $article = new ArticleResource(Article::find($request->id));
        if (isset($request->libelle)) {
            if (Article::where("libelle", ucfirst(strtolower($request->libelle)))->exists() && $article->libelle !== ucfirst(strtolower($request->libelle))) {
                return ["message" => "Ce libelle existe deja", "statut" => 404, "data" => []];
            }

            array_push($articleToUpdate, ["libelle" => ucfirst(strtolower($request->libelle))]);
        }
        if (isset($request->reference) && $request->reference) {
            array_push($articleToUpdate, ["reference" => $request->reference]);
        }
        if (isset($request->prix)) {
            array_push($articleToUpdate, ["prix" => $request->prix]);
        }
        if (isset($request->stock)) {
            array_push($articleToUpdate, ["stock" => $request->stock]);
        }
        if (isset($request->categorie_id)) {
            array_push($articleToUpdate, ["categorie_id" => $request->categorie_id]);
        }
        if (isset($request->image)) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/image', $fileName);
            array_push($articleToUpdate, ["photo" => $fileName]);
        }
        if (isset($request->fournisseurs)) {
            ArticleFournisseur::where("article_id", $request->id)->delete();
            try {
                $newArticle = Article::find($request->id);
                $newArticle->fournisseurs()->sync($request->fournisseurs);
            } catch (\Throwable $th) {
                return ["message" => $th->getMessage()];
            }
        }
        Article::where("id", $request->id)->update(...$articleToUpdate);
        return ["message" => "Données modifiées", "statut" => 200, "data" => new ArticleResource(Article::find($request->id))];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        try {
            Article::where("id", $request->article)->delete();
            return ["message" => "suppression reussi", "statut" => 200, "data" => []];
        } catch (\Throwable $th) {
            return ["message" => $th->getMessage(), "statut" => 404, "data" => []];
        }
        //
    }

    /* 
    * Methode pour restorer le articles supprimer avec le softDelete
    * En prevision de la creation d'une vue pour restaurer les articles supprimés.
     */
    public function restore(Request $request)
    {
        try {
            Article::where("id", $request->article)->restore();
            return ["message" => "Article restoré", "statut" => 200, "data" => []];
        } catch (\Throwable $th) {
            return ["message" => $th->getMessage(), "statut" => 404, "data" => []];
        }
    }
}
