<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'weight',
        'height',
        'width',
        'length',
        'package_count',
        'price_per_cubic_meter',
        'total_price',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'width' => 'decimal:2',
        'length' => 'decimal:2',
        'package_count' => 'integer',
        'price_per_cubic_meter' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Boot method to auto-calculate shipment price when items are modified.
     */
    protected static function boot()
    {
        parent::boot();

        // Recalculate shipment price after creating an item
        static::created(function ($item) {
            if ($item->shipment) {
                $item->shipment->calculateTotalPrice();
            }
        });

        // Recalculate shipment price after updating an item
        static::updated(function ($item) {
            if ($item->shipment) {
                $item->shipment->calculateTotalPrice();
            }
        });

        // Recalculate shipment price after deleting an item
        static::deleted(function ($item) {
            if ($item->shipment) {
                $item->shipment->calculateTotalPrice();
            }
        });
    }
}
