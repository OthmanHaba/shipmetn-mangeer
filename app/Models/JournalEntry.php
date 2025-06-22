<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'description',
        'reference_type',
        'reference_id',
        'shipment_id',
        'created_by_user_id',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    /**
     * Get the shipment associated with this journal entry.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the user who created this journal entry.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the journal lines for this entry.
     */
    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }
}
