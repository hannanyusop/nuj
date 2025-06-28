<?php

namespace App\Services;

use App\Models\Parcel;
use App\Models\UnregisteredParcel;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ParcelService
{

    const STATUS_REGISTERED = 1, STATUS_RECEIVED = 2, STATUS_OUTBOUND_TO_DROP_POINT = 3, STATUS_INBOUND_TO_DROP_POINT = 4;
    const STATUS_READY_TO_COLLECT = 5, STATUS_DELIVERED = 6, STATUS_RETURN = 7;

    const PENDING_STATUS = [self::STATUS_REGISTERED];
    const PROCESSED_STATUS = [self::STATUS_RECEIVED, self::STATUS_OUTBOUND_TO_DROP_POINT, self::STATUS_INBOUND_TO_DROP_POINT, self::STATUS_DELIVERED, self::STATUS_RETURN];

    const READY_TO_COLLECT_STATUS = [self::STATUS_READY_TO_COLLECT];

    const COMPLETED_STATUS = [self::STATUS_DELIVERED, self::STATUS_RETURN];


    public static function statuses($status = null){

        $statuses = [
            self::STATUS_REGISTERED => __('Parcel registered into NUJ System'),
            self::STATUS_RECEIVED   => __('Received By NUJ'),
            self::STATUS_OUTBOUND_TO_DROP_POINT => __('Outbound To Drop Point Office'),
            self::STATUS_INBOUND_TO_DROP_POINT => __('Inbound To Drop Point'),
            self::STATUS_READY_TO_COLLECT => __('Ready To Collect'),
            self::STATUS_DELIVERED => __('Delivered'),
            self::STATUS_RETURN => __('Returned')
        ];

        return (is_null($status))? $statuses : $statuses[$status] ?? __('Invalid status');
    }

    /**
     * Get status colors for UI display.
     */
    public static function getStatusColors($status = null): array|string
    {
        $statusColors = [
            self::STATUS_REGISTERED => 'bg-yellow-100 text-yellow-800',
            self::STATUS_RECEIVED => 'bg-blue-100 text-blue-800',
            self::STATUS_OUTBOUND_TO_DROP_POINT => 'bg-indigo-100 text-indigo-800',
            self::STATUS_INBOUND_TO_DROP_POINT => 'bg-purple-100 text-purple-800',
            self::STATUS_READY_TO_COLLECT => 'bg-green-100 text-green-800',
            self::STATUS_DELIVERED => 'bg-emerald-100 text-emerald-800',
            self::STATUS_RETURN => 'bg-red-100 text-red-800'
        ];

        return (is_null($status)) ? $statusColors : ($statusColors[$status] ?? 'bg-gray-100 text-gray-800');
    }

    /**
     * Get status icons for UI display.
     */
    public static function getStatusIcons($status = null): array|string
    {
        $statusIcons = [
            self::STATUS_REGISTERED => 'fas fa-clipboard-list',
            self::STATUS_RECEIVED => 'fas fa-box-open',
            self::STATUS_OUTBOUND_TO_DROP_POINT => 'fas fa-truck',
            self::STATUS_INBOUND_TO_DROP_POINT => 'fas fa-warehouse',
            self::STATUS_READY_TO_COLLECT => 'fas fa-hand-holding-box',
            self::STATUS_DELIVERED => 'fas fa-check-circle',
            self::STATUS_RETURN => 'fas fa-undo'
        ];

        return (is_null($status)) ? $statusIcons : ($statusIcons[$status] ?? 'fas fa-question');
    }

    /**
     * Generate complete status badge HTML with optional attributes.
     */
    public static function getStatusBadge($status, array $attributes = []): string
    {
        $defaultAttributes = [
            'class' => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . self::getStatusColors($status),
            'title' => self::statuses($status),
            'data-status' => $status,
            'data-status-text' => self::statuses($status)
        ];

        // Merge default attributes with custom attributes
        $attributes = array_merge($defaultAttributes, $attributes);
        
        // Build HTML attributes string
        $htmlAttributes = '';
        foreach ($attributes as $key => $value) {
            $htmlAttributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }

        return '<span' . $htmlAttributes . '>' . self::statuses($status) . '</span>';
    }

    /**
     * Generate status badge with icon.
     */
    public static function getStatusBadgeWithIcon($status, array $attributes = []): string
    {
        $defaultAttributes = [
            'class' => 'px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full ' . self::getStatusColors($status),
            'title' => self::statuses($status),
            'data-status' => $status,
            'data-status-text' => self::statuses($status)
        ];

        // Merge default attributes with custom attributes
        $attributes = array_merge($defaultAttributes, $attributes);
        
        // Build HTML attributes string
        $htmlAttributes = '';
        foreach ($attributes as $key => $value) {
            $htmlAttributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }

        $icon = self::getStatusIcons($status);
        
        return '<span' . $htmlAttributes . '><i class="' . $icon . ' mr-1"></i>' . self::statuses($status) . '</span>';
    }

    /**
     * Generate status badge with custom text.
     */
    public static function getStatusBadgeCustom($status, string $customText, array $attributes = []): string
    {
        $defaultAttributes = [
            'class' => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . self::getStatusColors($status),
            'title' => self::statuses($status),
            'data-status' => $status,
            'data-status-text' => self::statuses($status),
            'data-custom-text' => $customText
        ];

        // Merge default attributes with custom attributes
        $attributes = array_merge($defaultAttributes, $attributes);
        
        // Build HTML attributes string
        $htmlAttributes = '';
        foreach ($attributes as $key => $value) {
            $htmlAttributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }

        return '<span' . $htmlAttributes . '>' . htmlspecialchars($customText) . '</span>';
    }

    /**
     * Create a new parcel for a user.
     */
    public function createParcel(array $data, User $user): Parcel
    {
        try {
            Log::info('Creating parcel', [
                'user_id' => $user->id,
                'tracking_no' => $data['tracking_no']
            ]);

            // Handle file upload if present
            $invoicePath = null;
            if (isset($data['invoice']) && $data['invoice'] instanceof UploadedFile) {
                $invoicePath = $this->storeInvoice($data['invoice']);
            }

            // Create parcel
            $parcel = Parcel::create([
                'tracking_no' => $data['tracking_no'],
                'user_id' => $user->id,
                'receiver_name' => $data['receiver_name'],
                'phone_number' => $data['phone_number'],
                'description' => $data['description'],
                'quantity' => $data['quantity'],
                'price' => $data['price'],
                'invoice_url' => $invoicePath,
                'office_id' => $data['office_id'],
                'status' => self::STATUS_REGISTERED, // Pending
            ]);

            $unregistered = UnregisteredParcel::where([
                'tracking_no' => $data['tracking_no'],
                'parcel_id' => null
            ])->first();

            if($unregistered){
                $unregistered->parcel_id = $parcel->id;
                $unregistered->save();
            }

            Log::info('Parcel created successfully', [
                'parcel_id' => $parcel->id,
                'tracking_no' => $parcel->tracking_no
            ]);

            return $parcel;

        } catch (\Exception $e) {
            Log::error('Failed to create parcel', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'data' => Arr::except($data, ['invoice'])
            ]);

            throw $e;
        }
    }

    /**
     * Store invoice file and return the path.
     */
    private function storeInvoice(UploadedFile $file): string
    {
        $fileName = 'invoice_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs(self::getCurrentPath(), $fileName, 'public');
    }

    /**
     * Get parcels for a user with pagination, search and filtering.
     */
    public function getUserParcels(User $user, ?string $search = null, ?string $status = null, int $perPage = 15)
    {
        $query = $user->parcels()->with(['office']);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_no', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get a specific parcel for a user.
     */
    public function getUserParcel(User $user, int $parcelId): ?Parcel
    {
        return $user->parcels()
            ->with(['office'])
            ->where('id', $parcelId)
            ->first();
    }

    /**
     * Get recent parcels for a user.
     */
    public function getRecentParcels(User $user, int $limit = 5)
    {
        return $user->parcels()
            ->with(['office'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get parcel statistics for a user.
     */
    public function getUserParcelStats(User $user): array
    {
        $parcels = $user->parcels();

        return [
            'total' => $parcels->count(),
            'pending' => $parcels->whereIn('status', self::PENDING_STATUS)->count(),
            'in_transit' => $parcels->whereIn('status', [
                self::STATUS_RECEIVED,
                self::STATUS_OUTBOUND_TO_DROP_POINT,
                self::STATUS_INBOUND_TO_DROP_POINT
            ])->count(),
            'ready_to_collect' => $parcels->whereIn('status', self::READY_TO_COLLECT_STATUS)->count(),
            'delivered' => $parcels->whereIn('status', self::COMPLETED_STATUS)->count(),
        ];
    }

    /**
     * Check if user can access a specific parcel.
     */
    public function canUserAccessParcel(User $user, Parcel $parcel): bool
    {
        return $parcel->user_id === $user->id;
    }

    public static function getCurrentPath() : string
    {
        $current_year = date('Y');
        $current_month = date('m');
        return "invoice/$current_year/$current_month";
    }
} 