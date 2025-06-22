<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShipmentLeg extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'sequence_order',
        'from_node_id',
        'to_node_id',
        'status',
        'departure_timestamp',
        'arrival_timestamp',
    ];

    protected $casts = [
        'departure_timestamp' => 'datetime',
        'arrival_timestamp' => 'datetime',
    ];

    /**
     * Get the shipment for this leg.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the origin node for this leg.
     */
    public function originNode(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'origin_node_id');
    }

    /**
     * Get the destination node for this leg.
     */
    public function destinationNode(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'destination_node_id');
    }

    /**
     * Get the expenses for this shipment leg.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
