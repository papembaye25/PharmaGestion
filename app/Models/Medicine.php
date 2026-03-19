<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Medicine extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'quantity',
        'alert_threshold',
        'expiry_date',
        'description',
        'image'
    ];

    // ici on veut dire que expiry_date doit être traité comme une date, et price comme un nombre décimal avec 2 chiffres après la virgule
    protected $casts = [
        'expiry_date' => 'date',
        'price'       => 'decimal:2',
    ];

    /**
     * Un médicament appartient à une catégorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Un médicament peut apparaître dans plusieurs lignes de commande
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Un médicament peut apparaître dans plusieurs lignes de vente
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * ACCESSEUR : est-ce que le stock est faible ?
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->alert_threshold;
    }

    /**
     * ACCESSEUR : est-ce que le médicament est expiré ?
     * S'utilise comme : $medicine->is_expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date->isPast();
    }

    /**
     * ACCESSEUR : expire bientôt (dans moins de 30 jours) ?
     * S'utilise comme : $medicine->is_expiring_soon
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date->isFuture()
            && $this->expiry_date->diffInDays(Carbon::now()) <= 30;
    }
}