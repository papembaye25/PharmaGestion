<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');              // Nom du client
            $table->string('client_phone');             // Téléphone
            $table->text('client_address');             // Adresse de livraison
            $table->enum('payment_method', [            // Méthode de paiement
                'livraison',                            // Paiement à la livraison
                'whatsapp'                              // Via WhatsApp (Wave/Orange Money)
            ])->default('livraison');
            $table->enum('status', [                    // Statut de la commande
                'en_attente',
                'validee',
                'livree'
            ])->default('en_attente');
            $table->decimal('total', 10, 2)->default(0); // Total de la commande
            $table->text('notes')->nullable();           // Notes optionnelles du client
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};