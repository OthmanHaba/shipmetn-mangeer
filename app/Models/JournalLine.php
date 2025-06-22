<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'entry_type',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the journal entry that owns this line.
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /**
     * Get the account for this journal line.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }
}
