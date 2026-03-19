<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');   // Empêche de supprimer une catégorie qui a des médicaments
            $table->string('name');                        // Nom du médicament
            $table->decimal('price', 10, 2);               // Prix : 10 chiffres dont 2 après la virgule
            $table->integer('quantity')->default(0);       // Stock actuel, 0 par défaut
            $table->integer('alert_threshold')->default(10); // Seuil alerte stock faible
            $table->date('expiry_date');                   // Date d'expiration
            $table->text('description')->nullable();       // Description/posologie
            $table->string('image')->nullable();           // Chemin vers l'image uploadée
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};