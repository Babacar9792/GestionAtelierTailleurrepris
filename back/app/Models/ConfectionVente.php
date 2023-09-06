<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfectionVente extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function articleVente(): BelongsTo
    {
        return $this->belongsTo(ArticleVente::class);
    }
    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
