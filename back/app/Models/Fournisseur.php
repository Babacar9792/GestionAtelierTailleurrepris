<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model
{
    use HasFactory, SoftDeletes;
    public function articles() : BelongsToMany
    {
        return $this->belongsToMany(Article::class, "article_fournisseurs");
    }
}
