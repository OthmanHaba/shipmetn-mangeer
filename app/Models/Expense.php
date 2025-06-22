<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'shipment_leg_id',
        'vendor_name',
        'expense_date',
        'amount',
        'description',
        'account_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the shipment associated with this expense.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the shipment leg associated with this expense.
     */
    public function shipmentLeg(): BelongsTo
    {
        return $this->belongsTo(ShipmentLeg::class);
    }

    /**
     * Get the account for this expense.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }
}
