<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained() ->onDelete('cascade');   // Si on supprime une commande, ses lignes sont supprimées aussi
            $table->foreignId('medicine_id')->constrained() ->onDelete('restrict');  // On ne peut pas supprimer un médicament commandé
            $table->integer('quantity');               // Quantité commandée
            $table->decimal('unit_price', 10, 2);      // Prix au moment de la commande (important !)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};