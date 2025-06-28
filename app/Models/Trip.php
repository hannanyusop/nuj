<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    /**
     * Trip statuses
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
        'user_id',
        'runner_id',
        'code',
        'date',
        'destination_id',
        'status',
        'receive_code',
        'remark',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'status' => 'integer',
    ];

    /**
     * Get the user who created the trip.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the runner assigned to the trip.
     */
    public function runner()
    {
        return $this->belongsTo(User::class, 'runner_id');
    }

    /**
     * Get the destination office.
     */
    public function destination()
    {
        return $this->belongsTo(Office::class, 'destination_id');
    }

    /**
     * Get the parcels for this trip.
     */
    public function parcels()
    {
        return $this->hasMany(Parcel::class);
    }

    /**
     * Get the pickups for this trip.
     */
    public function pickups()
    {
        return $this->hasMany(Pickup::class);
    }

    /**
     * Scope for pending trips.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for in-progress trips.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope for completed trips.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for cancelled trips.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope for active trips (not cancelled).
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope for trips by date.
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope for trips by destination.
     */
    public function scopeByDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
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
     * Get trip display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->code . ' - ' . $this->date->format('M d, Y');
    }

    /**
     * Check if trip is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if trip is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if trip is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if trip is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Start the trip.
     */
    public function start(): void
    {
        $this->update(['status' => self::STATUS_IN_PROGRESS]);
    }

    /**
     * Complete the trip.
     */
    public function complete(): void
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
    }

    /**
     * Cancel the trip.
     */
    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    /**
     * Assign runner to trip.
     */
    public function assignRunner(int $runnerId): void
    {
        $this->update(['runner_id' => $runnerId]);
    }

    /**
     * Get parcels count.
     */
    public function getParcelsCountAttribute(): int
    {
        return $this->parcels()->count();
    }

    /**
     * Get pickups count.
     */
    public function getPickupsCountAttribute(): int
    {
        return $this->pickups()->count();
    }
} 