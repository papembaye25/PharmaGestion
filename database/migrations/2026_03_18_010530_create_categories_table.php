<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                             // clé primaire
            $table->string('name');                  // Nom de la catégorie
            $table->text('description')->nullable(); // Description optionnelle
            $table->timestamps();                  // created_at + updated_at automatiques
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');        // Supprime la table si on annule
    }
};