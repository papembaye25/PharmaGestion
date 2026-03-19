<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'medicine_id',
        'quantity',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    /**
     * Une ligne appartient à une commande
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Une ligne appartient à un médicament
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * ACCESSEUR : calcule le sous-total de cette ligne
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }
}