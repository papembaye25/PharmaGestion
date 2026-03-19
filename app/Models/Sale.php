<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['user_id', 'total', 'notes'];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    /**
     * Une vente appartient à un utilisateur (le pharmacien)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une vente a plusieurs lignes de produits
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}