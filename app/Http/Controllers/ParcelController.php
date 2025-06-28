<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParcelRequest;
use App\Models\Parcel;
use App\Models\Office;
use App\Services\ParcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParcelController extends Controller
{
    public function __construct(
        private ParcelService $parcelService
    ) {}

    /**
     * Show the add parcel form.
     */
    public function create()
    {
        $user = Auth::user();
        $dropPoints = Office::where('is_drop_point', 1)->get();
        
        return view('customer.add-parcel', compact('user', 'dropPoints'));
    }

    /**
     * Store a new parcel.
     */
    public function store(ParcelRequest $request)
    {
        try {
            $user = Auth::user();
            
            $parcel = $this->parcelService->createParcel($request->validated(), $user);

            return redirect()->route('customer.dashboard')
                ->with('success', 'Parcel registered successfully! Tracking number: ' . $parcel->tracking_no);

        } catch (\Exception $e) {
            return back()
                ->withInput($request->except(['invoice']))
                ->withErrors(['error' => 'Failed to register parcel. ' . $e->getMessage()]);
        }
    }

    /**
     * Display a listing of parcels for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $parcels = $this->parcelService->getUserParcels($user);
        
        return view('customer.parcels', compact('parcels'));
    }

    /**
     * Display the specified parcel.
     */
    public function show(Parcel $parcel)
    {
        $user = Auth::user();
        
        // Ensure user can only view their own parcels
        if (!$this->parcelService->canUserAccessParcel($user, $parcel)) {
            abort(403, 'Unauthorized access to parcel.');
        }

        return view('customer.parcel-detail', compact('parcel'));
    }
} 