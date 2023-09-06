<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleVenteRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\UpdateArticleVenteRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleVenteCollection;
use App\Http\Resources\ArticleVenteResource;
use App\Models\Article;
use App\Models\ArticleVente;
use App\Models\Categorie;
use App\Models\ConfectionVente;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Termwind\Components\Element;

class ArticleVenteController extends Controller
{

    public $categorieExiste = [];
    public function __construct()
    {
        $this->categorieExiste = Categorie::whereIn("libelle", ["Tissu", "Bouton", "Fils", "Tissus", "Boutons"])->pluck("id");
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $byPage = request()->query("item", 3);
        return new ArticleVenteCollection(ArticleVente::paginate($byPage));
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleVenteRequest $request)
    {
        
        if (!$this->venteHasGoodConfgection(array_map(function ($element) {
            return $element["article_id"];
        }, $request->confection_by_vente))["status"]) {
            return $this->venteHasGoodConfgection(array_map(function ($element) {
                return $element["article_id"];
            }, $request->confection_by_vente));
        }
        if ($request->marge > $request->prix_confection || $request->marge < 5000 || $request->marge > ($request->prix_confection / 3)) {
            return ["message" => "Marge incorrecte. Elle doit etre compris entre 5000 et ".$request->prix_confection / 3, "data" => [], "status" => false];
        }

        return DB::transaction(function () use ($request) {
            try {
                $articleVente = new ArticleVente();
                $articleVente->libelle = ucfirst(strtolower($request->libelle));
                $articleVente->categorie_id = $request->categorie['id'];
                $articleVente->promo = $request->promo;
                $articleVente->marge = $request->marge;
                $articleVente->prix_confection = $request->prix_confection;
                $articleVente->prix_vente = $request->prix_vente;
                $articleVente->photo = isset($request->photo) ? $request->photo : "no image";
                $articleVente->reference = $articleVente->getReference($request->categorie["id"], $request->libelle);
                // if ($request->hasFile('photo')) {
                //     $fileName = time() . '.' . $request->photo->extension();
                //     $request->photo->storeAs('public/images', $fileName);
                //     $articleVente->photo = 'images/' . $fileName;
                // } else {
                //     $articleVente->photo = 'pas dispo';
                // }
                $articleVente->save();
                $confectionVente = [];
                foreach ($request->confection_by_vente as $value) {
                     $articleVente->articles()->attach([$value["article_id"]=>['quantite_necessaire'=> $value['quantite_necessaire']]]);
                    //$confectionVente[] =[$value["article_id"]=>['quantite_necessaire'=> $value['quantite_necessaire']]];
                   // $confectionVente[] = ;
                }
               
                // $articleVente->articles()->attach([...$confectionVente]);
             
                return ["message" => "Insertion réussi", "data" => [new ArticleVenteResource($articleVente)], "status" => true];
            } catch (\Throwable $th) {
                throw $th;
           
                // return ["message" => $th->getMessage(), "status" => false, "data" => []];
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(ArticleVente $articleVente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateArticle(UpdateArticleVenteRequest $request)
    {
        // dd($request->id);
        // return $request->id;
        // ArticleVente::where("id", $request->id)->update($request->all());
        // return $request->all();

        $dataToupdate = [];
        $article = ArticleVente::where("id", $request->id)->first();
        // return $article;
        if (isset($request->libelle) && count(ArticleVente::where("libelle", $request->libelle)->get()) === 0) {
            // array_push($dataToupdate, ["libelle" => ucfirst(strtolower($request->libelle))]);
            $dataToupdate[] = ["libelle" => $request->libelle];

        }
        if (isset($request->categorie)) {
            // array_push($dataToupdate, ["categorie_id" => $request->categorie["id"]]);
            $dataToupdate[] = ["categorie_id" => $request->categorie['id']];
            
        }
        if (isset($request->promo)) {
            // array_push($dataToupdate, ["promo" => $request->promo]);
            $dataToupdate[] = ["promo" => $request->promo];

        }
        if (isset($request->marge) && $request->marge <= $article->prix_confection && $request->marge >= 5000) {

            // array_push($dataToupdate, ["marge" => $request->marge]);
            $dataToupdate[] = ["marge" => $request->marge];

        }
        if (isset($request->prix_confection)) {
            // array_push($dataToupdate, ["prix_confection" => $request->prix_confection]);
            $dataToupdate[] = ["prix_confection" => $request->prix_confection];

        }
        if (isset($request->prix_vente)) {
            // array_push($dataToupdate, ["prix_vente" => $request->prix_vente]);
            $dataToupdate[] = ["prix_vente" => $request->prix_vente];

        }
        if (isset($request->quantite_stock)) {
            // array_push($dataToupdate, ["quantite_stock" => $request->quantite_stock]);
            $dataToupdate[] = ["quantite_stock" => $request->quantite_stock];

        }
        if (isset($request->reference)) {
            // array_push($dataToupdate, ["reference" => $request->reference]);
            $dataToupdate[] = ["reference" => $request->reference];
        }
        if (isset($request->confection_by_vente)) {
            if ($this->venteHasGoodConfgection(array_map(function ($element) {
                return $element["article_id"];
            }, $request->confection_by_vente))) {
                // $confectionVente = [];
                // foreach ($request->confection_by_vente as $value) {
                //     $confectionVente[] = ["article_id" => $value["article_id"], "quantite_necessaire" => $value['quantite_necessaire']];
                // }
                foreach ($request->confection_by_vente as $value) {
                     $article->articles()->sync([$value["article_id"]=>['quantite_necessaire'=> $value['quantite_necessaire']]]);
                    
                }
                // $article->articles()->sync($confectionVente);
                // $article->articles()->sync($request->confection_by_vente);
            }
        }
        if (isset($request->photo)) {
            // if ($request->hasFile('photo')) {
            //     $fileName = time() . '.' . $request->photo->extension();
            //     $request->photo->storeAs('public/images', $fileName);
                // array_push($dataToupdate, ["photo" => $request->photo]);
                $dataToupdate[] = ["photo" => $request->photo];
            // }
        }

        ArticleVente::where("id", $request->id)->update(...$dataToupdate);
        return ["message" => "Données mise à jours", "status" => true, "data" => [new ArticleVenteResource(ArticleVente::find($request->id))]];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        // return $request->articleVente;
        ArticleVente::whereIn("id", [$request->articleVente])->delete();
        return ["message" => "Données supprimées", "status" => true, "data" => []];
    }

    public function venteHasGoodConfgection($article_ids)
    {
        if (count($this->categorieExiste) < 3) {
            return ["message" => "Vous devez enregistrer au moins les categories tissus, boutons et fils pour pouvoir enregistrer un article de vente", "status" => false, "data" => []];
        }
        $articleConfection = Article::whereIn("categorie_id", [...$this->categorieExiste])->get();
        if (count($articleConfection) < 3) {
            return ["message" => "Vous devez avoir au moins un article de confection de categorie bouton, un autre de categorie fils et un autre de categorie tissu", "status" => false, "data" => []];
        }
        $array_mapped = array_map(function ($array_item) {
            return $array_item->id;
        }, [...$articleConfection]);
        $arrayIntersect = [...array_intersect([...$array_mapped], $article_ids)];
        if (count($arrayIntersect) < 3) {
            return ["message" => "Vous devez choisir au moins un article de confection de categorie tissue, un auitre de categorie bouton et un autre de categorie fils", "status" => false, "data" => []];
        }
        $filteredArticles = array_filter([...$articleConfection], function ($article) use ($arrayIntersect) {
            return in_array($article->id, $arrayIntersect);
        });
        $filteredArticles = array_map(function ($value) {
            return $value->categorie_id;
        }, [...$filteredArticles]);
        if (count(array_unique([...$filteredArticles])) < 3) {
            return ["meassage" => "Vous devez choisir au moins un article de confection de categorie tissue, un auitre de categorie bouton et un autre de categorie fils", "status" => false, "data" => []];
        }
        return ["message" => '', "status" => true, "data" => []];
    }

    public function search(HttpFoundationRequest $request)
    {
        
        return ["message" => "", "status" => true, "data" => ArticleVenteResource::collection(ArticleVente::where("libelle", "like", ucfirst(strtolower($request['libelle'])) . "%")->get())];
    }


    // public funct
}
