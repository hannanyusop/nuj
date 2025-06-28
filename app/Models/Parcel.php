<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    /**
     * Parcel statuses
     */
    const STATUS_PENDING = 0;
    const STATUS_REGISTERED = 1;
    const STATUS_IN_TRANSIT = 2;
    const STATUS_READY_TO_COLLECT = 3;
    const STATUS_COLLECTED = 4;
    const STATUS_DELIVERED = 5;
    const STATUS_CANCELLED = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trip_id',
        'user_id',
        'office_id',
        'status',
        'tracking_no',
        'receiver_name',
        'phone_number',
        'pickup_name',
        'pickup_info',
        'pickup_id',
        'serve_by',
        'pickup_datetime',
        'order_origin',
        'description',
        'quantity',
        'price',
        'tax',
        'invoice_url',
        'invoice_path',
        'collection_point',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pickup_datetime' => 'datetime',
        'quantity' => 'integer',
        'price' => 'integer',
        'tax' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Get the user that owns the parcel.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the office that the parcel belongs to.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get the trip that the parcel belongs to.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the pickup for the parcel.
     */
    public function pickup()
    {
        return $this->belongsTo(Pickup::class);
    }

    /**
     * Get the user who served the parcel.
     */
    public function servedBy()
    {
        return $this->belongsTo(User::class, 'serve_by');
    }

    /**
     * Scope for pending parcels.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for registered parcels.
     */
    public function scopeRegistered($query)
    {
        return $query->where('status', self::STATUS_REGISTERED);
    }

    /**
     * Scope for in-transit parcels.
     */
    public function scopeInTransit($query)
    {
        return $query->where('status', self::STATUS_IN_TRANSIT);
    }

    /**
     * Scope for ready to collect parcels.
     */
    public function scopeReadyToCollect($query)
    {
        return $query->where('status', self::STATUS_READY_TO_COLLECT);
    }

    /**
     * Scope for collected parcels.
     */
    public function scopeCollected($query)
    {
        return $query->where('status', self::STATUS_COLLECTED);
    }

    /**
     * Scope for delivered parcels.
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    /**
     * Scope for cancelled parcels.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope for active parcels (not cancelled).
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Get status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_REGISTERED => 'Registered',
            self::STATUS_IN_TRANSIT => 'In Transit',
            self::STATUS_READY_TO_COLLECT => 'Ready to Collect',
            self::STATUS_COLLECTED => 'Collected',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Get total price including tax.
     */
    public function getTotalPriceAttribute(): int
    {
        return ($this->price ?? 0) + ($this->tax ?? 0);
    }

    /**
     * Check if parcel is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if parcel is ready to collect.
     */
    public function isReadyToCollect(): bool
    {
        return $this->status === self::STATUS_READY_TO_COLLECT;
    }

    /**
     * Check if parcel is collected.
     */
    public function isCollected(): bool
    {
        return $this->status === self::STATUS_COLLECTED;
    }

    /**
     * Check if parcel is delivered.
     */
    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Check if parcel is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Mark parcel as ready to collect.
     */
    public function markAsReadyToCollect(): void
    {
        $this->update(['status' => self::STATUS_READY_TO_COLLECT]);
    }

    /**
     * Mark parcel as collected.
     */
    public function markAsCollected(int $servedBy = null): void
    {
        $this->update([
            'status' => self::STATUS_COLLECTED,
            'serve_by' => $servedBy,
            'pickup_datetime' => now(),
        ]);
    }

    /**
     * Mark parcel as delivered.
     */
    public function markAsDelivered(): void
    {
        $this->update(['status' => self::STATUS_DELIVERED]);
    }

    /**
     * Cancel parcel.
     */
    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }
} 