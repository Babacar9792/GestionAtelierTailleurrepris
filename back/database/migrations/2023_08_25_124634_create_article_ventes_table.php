<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_ventes', function (Blueprint $table) {
            $table->id();
            $table->string("libelle")->unique();
            $table->float("prix_confection");
            $table->float("prix_vente")->default(0);
            $table->float("marge")->default(0);
            $table->string("photo");
            $table->string("reference");
            $table->integer("quantite_stock")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_ventes');
    }
};
