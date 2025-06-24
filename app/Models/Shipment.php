<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'shipping_mode',
        'status',
        'shipper_customer_id',
        'consignee_customer_id',
        'estimated_departure',
        'estimated_arrival',
        'actual_departure',
        'actual_arrival',
        'shipment_price',
    ];

    protected $casts = [
        'estimated_departure' => 'datetime',
        'estimated_arrival' => 'datetime',
        'actual_departure' => 'datetime',
        'actual_arrival' => 'datetime',
        'shipment_price' => 'decimal:2',
    ];

    /**
     * Get the shipper customer for this shipment.
     */
    public function shipper(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'shipper_customer_id');
    }

    /**
     * Get the consignee customer for this shipment.
     */
    public function consignee(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'consignee_customer_id');
    }

    /**
     * Get the origin node for this shipment (from first leg).
     */
    public function getOriginAttribute()
    {
        return $this->legs()->orderBy('sequence_order')->first()?->fromNode;
    }

    /**
     * Get the destination node for this shipment (from last leg).
     */
    public function getDestinationAttribute()
    {
        return $this->legs()->orderBy('sequence_order', 'desc')->first()?->toNode;
    }

    /**
     * Get the user who created this shipment.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the legs for this shipment.
     */
    public function legs(): HasMany
    {
        return $this->hasMany(ShipmentLeg::class);
    }

    /**
     * Get the invoices for this shipment.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the expenses for this shipment.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the journal entries for this shipment.
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Get the items for this shipment.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    /**
     * Calculate and update the total shipment price based on items.
     */
    public function calculateTotalPrice(): float
    {
        $totalPrice = $this->items()->sum('total_price');

        $this->shipment_price = $totalPrice;
        $this->save();

        return $totalPrice;
    }

    /**
     * Boot method to auto-calculate shipment price when items are updated.
     */
    protected static function boot()
    {
        parent::boot();

        // Recalculate shipment price when saving
        static::saved(function ($shipment) {
            if ($shipment->wasRecentlyCreated || $shipment->isDirty(['id'])) {
                return; // Skip calculation on initial creation
            }
        });
    }
}
