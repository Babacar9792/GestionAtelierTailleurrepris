<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;
    public function fournisseurs() : BelongsToMany
    {
        return $this->belongsToMany(Fournisseur::class, "article_fournisseurs");
    }
    public function categorie() : BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function articleVentes() : BelongsToMany
    {
        return $this->belongsToMany(ArticleVente::class, "confection_ventes");
    }
    public function confectionVentes() : HasMany{
        return $this->hasMany(ConfectionVente::class);
    }
}
