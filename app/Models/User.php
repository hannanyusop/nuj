<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * User types
     */
    const TYPE_ADMIN = 'admin';
    const TYPE_MANAGER = 'manager';
    const TYPE_STAFF = 'staff';
    const TYPE_RUNNER = 'runner';
    const TYPE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'office_id',
        'name',
        'email',
        'default_drop_point',
        'phone_number',
        'address',
        'password',
        'active',
        'timezone',
        'image',
        'provider',
        'provider_id',
        'wallet',
        'wallet_total',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'active' => 'boolean',
        'to_be_logged_out' => 'boolean',
        'wallet' => 'decimal:2',
        'wallet_total' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the office that the user belongs to.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the default drop point office.
     */
    public function defaultDropPoint()
    {
        return $this->belongsTo(Office::class, 'default_drop_point');
    }

    /**
     * Get the parcels for the user.
     */
    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }

    /**
     * Get the trips created by the user.
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get the pickups for the user.
     */
    public function pickups()
    {
        return $this->hasMany(Pickup::class);
    }

    /**
     * Get the parcels served by the user.
     */
    public function servedParcels()
    {
        return $this->hasMany(Parcel::class, 'serve_by');
    }

    /**
     * Get the pickups served by the user.
     */
    public function servedPickups()
    {
        return $this->hasMany(Pickup::class, 'serve_by');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN;
    }

    /**
     * Check if user is manager.
     */
    public function isManager(): bool
    {
        return $this->type === self::TYPE_MANAGER;
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->type === self::TYPE_STAFF;
    }

    /**
     * Check if user is runner.
     */
    public function isRunner(): bool
    {
        return $this->type === self::TYPE_RUNNER;
    }

    /**
     * Check if user is customer.
     */
    public function isCustomer(): bool
    {
        return $this->type === self::TYPE_USER;
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    /**
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope for customers only.
     */
    public function scopeCustomers($query)
    {
        return $query->where('type', self::TYPE_USER);
    }

    /**
     * Scope for staff members.
     */
    public function scopeStaff($query)
    {
        return $query->whereIn('type', [self::TYPE_STAFF, self::TYPE_MANAGER]);
    }

    /**
     * Scope for admins.
     */
    public function scopeAdmins($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }

    /**
     * Scope for runners.
     */
    public function scopeRunners($query)
    {
        return $query->where('type', self::TYPE_RUNNER);
    }

    /**
     * Get user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get user's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . ucfirst($this->type) . ')';
    }

    /**
     * Get user's wallet balance.
     */
    public function getWalletBalanceAttribute(): float
    {
        return (float) $this->wallet;
    }

    /**
     * Update last login information.
     */
    public function updateLastLogin(string $ip = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }

    /**
     * Check if user needs to be logged out.
     */
    public function needsLogout(): bool
    {
        return $this->to_be_logged_out;
    }

    /**
     * Mark user for logout.
     */
    public function markForLogout(): void
    {
        $this->update(['to_be_logged_out' => true]);
    }

    /**
     * Clear logout mark.
     */
    public function clearLogoutMark(): void
    {
        $this->update(['to_be_logged_out' => false]);
    }
}
