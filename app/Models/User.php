<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles , Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role associated with the user.
     */

    /**
     * Get all shipments created by this user.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'created_by_user_id');
    }

    /**
     * Get all journal entries created by this user.
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'created_by_user_id');
    }

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }
}
