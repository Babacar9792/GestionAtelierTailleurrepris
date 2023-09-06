<?php

namespace App\Models;

use Faker\Core\Number;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ArticleVente extends Model
{
    use HasFactory, SoftDeletes;
    public function articles() : BelongsToMany
    {
        return $this->belongsToMany(Article::class, "confection_ventes")->withPivot("quantite_necessaire");

    }
    public function categorie() : BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function getReference($categorie_id,$libelleArticle)
    {
        $categorie = Categorie::where("id", $categorie_id)->first();
       return "REF-". strtoupper($categorie->libelle)."-".strtoupper(substr($libelleArticle, 0, 3))."-".count( DB::table("article_ventes")->where("categorie_id", $categorie)->get()) + 1;
    }

    public function confectionVente() : HasMany
    {
        return $this->hasMany(ConfectionVente::class);
    }

    
}
