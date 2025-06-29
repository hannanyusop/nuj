<?php

namespace App\Http\Controllers;

use App\Services\ParcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct(
        private ParcelService $parcelService
    ) {}

    /**
     * Show customer dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $stats = $this->parcelService->getUserParcelStats($user);
        $recentParcels = $this->parcelService->getRecentParcels($user);

        // Extract stats for dashboard cards
        $totalParcels = $stats['total'] ?? 0;
        $deliveredParcels = $stats['delivered'] ?? 0;
        $inTransitParcels = $stats['in_transit'] ?? 0;
        $readyToCollectParcels = $stats['ready_to_collect'] ?? 0;

        return view('customer.dashboard', compact(
            'stats',
            'recentParcels',
            'totalParcels',
            'deliveredParcels',
            'inTransitParcels',
            'readyToCollectParcels'
        ));
    }

    /**
     * Show customer profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Show customer parcels list with search and filtering.
     */
    public function parcels(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $status = $request->get('status');
        
        $parcels = $this->parcelService->getUserParcels($user, $search, $status);
        
        return view('customer.parcels', compact('parcels'));
    }

    /**
     * Show individual parcel details.
     */
    public function showParcel($id)
    {
        $user = Auth::user();
        $parcel = $this->parcelService->getUserParcel($user, $id);
        
        if (!$parcel) {
            abort(404, 'Parcel not found');
        }
        
        return view('customer.parcel-details', compact('parcel'));
    }

    /**
     * Show add parcel form.
     */
    public function addParcel()
    {
        return view('customer.add-parcel');
    }
} 