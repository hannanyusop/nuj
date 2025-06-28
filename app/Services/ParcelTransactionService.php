<?php

namespace App\Services;

use App\Models\Parcel;
use App\Models\ParcelTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ParcelTransactionService
{
    /**
     * Add a new transaction for a parcel.
     */
    public function addParcelTransaction(int $parcel_id, string $remark, ?User $user = null): bool
    {
        try {
            $parcel = Parcel::find($parcel_id);

            if (!$parcel) {
                Log::warning('Parcel not found for transaction', [
                    'parcel_id' => $parcel_id,
                    'user_id' => $user?->id ?? Auth::id()
                ]);
                return false;
            }

            $transaction = new ParcelTransaction();
            $transaction->user_id = $user?->id ?? Auth::id();
            $transaction->parcel_id = $parcel_id;
            $transaction->remark = $remark;
            $transaction->save();

            Log::info('Parcel transaction created successfully', [
                'transaction_id' => $transaction->id,
                'parcel_id' => $parcel_id,
                'user_id' => $transaction->user_id,
                'remark' => $remark
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to create parcel transaction', [
                'parcel_id' => $parcel_id,
                'user_id' => $user?->id ?? Auth::id(),
                'remark' => $remark,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Add a transaction when a parcel is created.
     */
    public function addParcelCreatedTransaction(int $parcel_id, ?User $user = null): bool
    {
        $remark = 'Parcel registered successfully';
        return $this->addParcelTransaction($parcel_id, $remark, $user);
    }

    /**
     * Add a transaction when parcel status is updated.
     */
    public function addStatusUpdateTransaction(int $parcel_id, string $oldStatus, string $newStatus, ?User $user = null): bool
    {
        $statusText = [
            0 => 'Pending',
            1 => 'In Transit', 
            2 => 'Delivered',
            3 => 'Ready to Collect'
        ];

        $remark = sprintf(
            'Status updated from %s to %s',
            $statusText[$oldStatus] ?? 'Unknown',
            $statusText[$newStatus] ?? 'Unknown'
        );

        return $this->addParcelTransaction($parcel_id, $remark, $user);
    }

    /**
     * Add a transaction for parcel pickup.
     */
    public function addPickupTransaction(int $parcel_id, ?User $user = null): bool
    {
        $remark = 'Parcel picked up for delivery';
        return $this->addParcelTransaction($parcel_id, $remark, $user);
    }

    /**
     * Add a transaction for parcel delivery.
     */
    public function addDeliveryTransaction(int $parcel_id, ?User $user = null): bool
    {
        $remark = 'Parcel delivered successfully';
        return $this->addParcelTransaction($parcel_id, $remark, $user);
    }

    /**
     * Add a transaction for parcel collection.
     */
    public function addCollectionTransaction(int $parcel_id, ?User $user = null): bool
    {
        $remark = 'Parcel ready for collection';
        return $this->addParcelTransaction($parcel_id, $remark, $user);
    }

    /**
     * Get all transactions for a specific parcel.
     */
    public function getParcelTransactions(int $parcel_id): \Illuminate\Database\Eloquent\Collection
    {
        return ParcelTransaction::where('parcel_id', $parcel_id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent transactions for a user.
     */
    public function getUserRecentTransactions(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return ParcelTransaction::where('user_id', $user->id)
            ->with(['parcel', 'user'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get transaction statistics for a user.
     */
    public function getUserTransactionStats(User $user): array
    {
        $transactions = ParcelTransaction::where('user_id', $user->id);

        return [
            'total_transactions' => $transactions->count(),
            'today_transactions' => $transactions->whereDate('created_at', today())->count(),
            'this_week_transactions' => $transactions->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_transactions' => $transactions->whereMonth('created_at', now()->month)->count(),
        ];
    }
} 