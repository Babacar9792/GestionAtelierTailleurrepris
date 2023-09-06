<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleVenteController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\FournisseurController;
use App\Models\Article;
use App\Models\ArticleVente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource("categorie", CategorieController::class);
Route::apiResource("article", ArticleController::class);
Route::apiResource("fournisseur", FournisseurController::class);
Route::post("article/update", [ArticleController::class, "updateArticle"]);
Route::put("article/restoration/{article}", [ArticleController::class, "restore"]);
Route::apiResource("articleVente", ArticleVenteController::class)->only(['index', 'show', 'destroy', 'store']);
Route::post("articleVente/update", [ArticleVenteController::class, "updateArticle"]);


Route::get("/articleVente/search/{libelle}", [ArticleVenteController::class, "search"])->name("ArticleVente.search");

Route::get("/test/{categorie}", [ArticleVenteController::class, "getReference"]);
