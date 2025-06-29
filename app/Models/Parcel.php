<?php

namespace App\Models;

use App\Services\ParcelService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;

    // Status constants (matching ParcelService)
    const STATUS_REGISTERED = 1;
    const STATUS_RECEIVED = 2;
    const STATUS_OUTBOUND_TO_DROP_POINT = 3;
    const STATUS_INBOUND_TO_DROP_POINT = 4;
    const STATUS_READY_TO_COLLECT = 5;
    const STATUS_DELIVERED = 6;
    const STATUS_RETURN = 7;

    // Legacy status constants (for backward compatibility)
    const STATUS_PENDING = 0;
    const STATUS_IN_TRANSIT = 1;
    const STATUS_COLLECTED = 2;
    const STATUS_CANCELLED = 8;

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
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'status_badge',
        'status_badge_with_icon',
        'status_text',
        'status_label',
        'status_color',
        'status_icon',
        'total_price'
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
        return ParcelService::statuses($this->status);
    }

    /**
     * Get status label (short version).
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_REGISTERED => 'Registered',
            self::STATUS_RECEIVED => 'Received',
            self::STATUS_OUTBOUND_TO_DROP_POINT => 'Outbound',
            self::STATUS_INBOUND_TO_DROP_POINT => 'Inbound',
            self::STATUS_READY_TO_COLLECT => 'Ready to Collect',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_RETURN => 'Returned'
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Get status color classes.
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_REGISTERED => 'bg-yellow-100 text-yellow-800',
            self::STATUS_RECEIVED => 'bg-blue-100 text-blue-800',
            self::STATUS_OUTBOUND_TO_DROP_POINT => 'bg-indigo-100 text-indigo-800',
            self::STATUS_INBOUND_TO_DROP_POINT => 'bg-purple-100 text-purple-800',
            self::STATUS_READY_TO_COLLECT => 'bg-green-100 text-green-800',
            self::STATUS_DELIVERED => 'bg-emerald-100 text-emerald-800',
            self::STATUS_RETURN => 'bg-red-100 text-red-800'
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get status icon class.
     */
    public function getStatusIconAttribute(): string
    {
        $icons = [
            self::STATUS_REGISTERED => 'fas fa-clipboard-list',
            self::STATUS_RECEIVED => 'fas fa-box-open',
            self::STATUS_OUTBOUND_TO_DROP_POINT => 'fas fa-truck',
            self::STATUS_INBOUND_TO_DROP_POINT => 'fas fa-warehouse',
            self::STATUS_READY_TO_COLLECT => 'fas fa-hand-holding-box',
            self::STATUS_DELIVERED => 'fas fa-check-circle',
            self::STATUS_RETURN => 'fas fa-undo'
        ];

        return $icons[$this->status] ?? 'fas fa-question';
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->generateStatusBadge();
    }

    /**
     * Get status badge with icon HTML.
     */
    public function getStatusBadgeWithIconAttribute(): string
    {
        return $this->generateStatusBadge(true);
    }

    /**
     * Generate status badge HTML.
     */
    private function generateStatusBadge(bool $withIcon = false, array $attributes = []): string
    {
        $defaultAttributes = [
            'class' => 'px-2 inline-flex ' . ($withIcon ? 'items-center ' : '') . 'text-xs leading-5 font-semibold rounded-full ' . $this->status_color,
            'title' => $this->status_text,
            'data-status' => $this->status,
            'data-status-text' => $this->status_text
        ];

        // Merge default attributes with custom attributes
        $attributes = array_merge($defaultAttributes, $attributes);
        
        // Build HTML attributes string
        $htmlAttributes = '';
        foreach ($attributes as $key => $value) {
            $htmlAttributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }

        $content = $withIcon ? '<i class="' . $this->status_icon . ' mr-1"></i>' . $this->status_text : $this->status_text;
        
        return '<span' . $htmlAttributes . '>' . $content . '</span>';
    }

    /**
     * Get status badge with custom attributes.
     */
    public function getStatusBadgeWithAttributes(array $attributes = []): string
    {
        return $this->generateStatusBadge(false, $attributes);
    }

    /**
     * Get status badge with icon and custom attributes.
     */
    public function getStatusBadgeWithIconAndAttributes(array $attributes = []): string
    {
        return $this->generateStatusBadge(true, $attributes);
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