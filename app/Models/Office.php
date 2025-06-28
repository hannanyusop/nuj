<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    /**
     * Office types
     */
    const TYPE_BOO = 'boo'; // Business Owner Office
    const TYPE_DO = 'do';   // Drop Point Office
    const TYPE_RO = 'ro';   // Receiving Office

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'is_drop_point',
        'address',
        'location',
        'operation_day',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_drop_point' => 'boolean',
    ];

    /**
     * Get the users that belong to this office.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the parcels for this office.
     */
    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }

    /**
     * Get the pickups for this office.
     */
    public function pickups()
    {
        return $this->hasMany(Pickup::class);
    }

    /**
     * Get the trips with this office as destination.
     */
    public function destinationTrips()
    {
        return $this->hasMany(Trip::class, 'destination_id');
    }

    /**
     * Get the users with this office as default drop point.
     */
    public function defaultDropPointUsers()
    {
        return $this->hasMany(User::class, 'default_drop_point');
    }

    /**
     * Scope for drop point offices.
     */
    public function scopeDropPoints($query)
    {
        return $query->where('is_drop_point', true);
    }

    /**
     * Scope for non-drop point offices.
     */
    public function scopeNonDropPoints($query)
    {
        return $query->where('is_drop_point', false);
    }

    /**
     * Scope for active offices.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get office display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . $this->code . ')';
    }

    /**
     * Check if office is a drop point.
     */
    public function isDropPoint(): bool
    {
        return $this->is_drop_point;
    }

    /**
     * Get operation days as array.
     */
    public function getOperationDaysAttribute(): array
    {
        if (!$this->operation_day) {
            return [];
        }

        return json_decode($this->operation_day, true) ?? [];
    }

    /**
     * Set operation days from array.
     */
    public function setOperationDaysAttribute(array $days): void
    {
        $this->attributes['operation_day'] = json_encode($days);
    }
} 