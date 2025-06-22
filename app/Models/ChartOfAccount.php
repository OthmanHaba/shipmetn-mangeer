<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'account_name',
        'account_type',
        'parent_account_id',
    ];

    /**
     * Get the parent account.
     */
    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_account_id');
    }

    /**
     * Get the child accounts.
     */
    public function childAccounts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_account_id');
    }

    /**
     * Get the journal lines for this account.
     */
    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class, 'account_id');
    }

    /**
     * Get the expenses for this account.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'account_id');
    }
}
