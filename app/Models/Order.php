<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_name',
        'client_phone',
        'client_address',
        'payment_method',
        'status',
        'total',
        'notes'
    ];

    /**
     * Une commande a plusieurs lignes de produits
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * ACCESSEUR : retourne un badge coloré selon le statut
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'en_attente' => '<span class="badge bg-warning">En attente</span>',
            'validee'    => '<span class="badge bg-primary">Validée</span>',
            'livree'     => '<span class="badge bg-success">Livrée</span>',
            default      => '<span class="badge bg-secondary">Inconnu</span>',
        };
    }
}