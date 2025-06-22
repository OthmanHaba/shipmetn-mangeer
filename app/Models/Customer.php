<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'contact_person',
        'email',
        'phone_number',
        'address',
    ];

    /**
     * Get all shipments where this customer is the shipper.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'shipper_customer_id');
    }

    /**
     * Get all shipments where this customer is the consignee.
     */
    public function consigneeShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'consignee_customer_id');
    }

    /**
     * Get all invoices for this customer.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
