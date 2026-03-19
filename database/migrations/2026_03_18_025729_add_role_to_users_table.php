<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // On ajoute la colonne role après la colonne name
            $table->string('role')->default('admin')->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Si on annule cette migration, on supprime la colonne
            $table->dropColumn('role');
        });
    }
};