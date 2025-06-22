<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'shipment_id',
        'customer_id',
        'issue_date',
        'due_date',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the shipment for this invoice.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the customer for this invoice.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
