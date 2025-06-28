<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pickup extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Pickup statuses
     */
    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trip_id',
        'office_id',
        'user_id',
        'status',
        'serve_by',
        'code',
        'pickup_name',
        'prof_of_delivery',
        'pickup_datetime',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pickup_datetime' => 'datetime',
        'status' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the trip that the pickup belongs to.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the office that the pickup belongs to.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the user who owns the pickup.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who served the pickup.
     */
    public function servedBy()
    {
        return $this->belongsTo(User::class, 'serve_by');
    }

    /**
     * Get the parcels for this pickup.
     */
    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }

    /**
     * Scope for pending pickups.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for in-progress pickups.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope for completed pickups.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for cancelled pickups.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope for active pickups (not cancelled).
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope for pickups by office.
     */
    public function scopeByOffice($query, $officeId)
    {
        return $query->where('office_id', $officeId);
    }

    /**
     * Scope for pickups by trip.
     */
    public function scopeByTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }

    /**
     * Get status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Get pickup display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->code . ' - ' . ($this->pickup_name ?? 'Unnamed Pickup');
    }

    /**
     * Check if pickup is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if pickup is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if pickup is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if pickup is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Start the pickup.
     */
    public function start(): void
    {
        $this->update(['status' => self::STATUS_IN_PROGRESS]);
    }

    /**
     * Complete the pickup.
     */
    public function complete(int $servedBy = null): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'serve_by' => $servedBy,
            'pickup_datetime' => now(),
        ]);
    }

    /**
     * Cancel the pickup.
     */
    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    /**
     * Get parcels count.
     */
    public function getParcelsCountAttribute(): int
    {
        return $this->parcels()->count();
    }
} 