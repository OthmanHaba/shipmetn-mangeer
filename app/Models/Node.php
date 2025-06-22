<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Node extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_name',
        'node_type',
        'address',
        'city',
        'country',
    ];

    /**
     * Get all shipments originating from this node.
     */
    public function originShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'origin_node_id');
    }

    /**
     * Get all shipments destined for this node.
     */
    public function destinationShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'destination_node_id');
    }

    /**
     * Get all shipment legs starting from this node.
     */
    public function departureLegs(): HasMany
    {
        return $this->hasMany(ShipmentLeg::class, 'from_node_id');
    }

    /**
     * Get all shipment legs ending at this node.
     */
    public function arrivalLegs(): HasMany
    {
        return $this->hasMany(ShipmentLeg::class, 'to_node_id');
    }
}
