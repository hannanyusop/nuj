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
                'status' => 0, // Pending
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
            'pending' => $parcels->where('status', 0)->count(),
            'ready_to_collect' => $parcels->where('status', 3)->count(),
            'in_transit' => $parcels->where('status', 1)->count(),
            'delivered' => $parcels->where('status', 2)->count(),
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