<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Champs qu'on autorise à remplir en masse (protection contre les attaques)
    protected $fillable = ['name', 'description'];

    /**
     * Une catégorie a plusieurs médicaments
     * Eloquent cherche automatiquement category_id dans la table medicines
     */
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}