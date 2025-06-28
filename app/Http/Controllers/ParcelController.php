<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ParcelController extends Controller
{
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
    public function store(Request $request)
    {
        try {
            Log::info('Parcel creation request received', [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['invoice']),
                'has_file' => $request->hasFile('invoice')
            ]);

            $request->validate([
                'tracking_no' => 'required|string|max:50|unique:parcels',
                'receiver_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'description' => 'required|string|max:500',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'office_id' => 'required|exists:offices,id',
            ]);

            $user = Auth::user();
            
            // Handle file upload
            $invoicePath = null;
            if ($request->hasFile('invoice')) {
                $file = $request->file('invoice');
                $fileName = 'invoice_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $invoicePath = $file->storeAs('invoices', $fileName, 'public');
            }

            // Create parcel
            $parcel = Parcel::create([
                'tracking_no' => $request->tracking_no,
                'user_id' => $user->id,
                'receiver_name' => $request->receiver_name,
                'phone_number' => $request->phone_number,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'invoice_url' => $invoicePath,
                'office_id' => $request->office_id,
                'status' => 0, // Pending
            ]);

            Log::info('Parcel created successfully', [
                'parcel_id' => $parcel->id,
                'tracking_no' => $parcel->tracking_no,
                'user_id' => $user->id
            ]);

            return redirect()->route('customer.dashboard')
                ->with('success', 'Parcel registered successfully! Tracking number: ' . $parcel->tracking_no);

        } catch (\Exception $e) {

            Log::error('Failed to create parcel', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['invoice'])
            ]);

            return back()
                ->withInput($request->except(['invoice']))
                ->withErrors(['error' => 'Failed to register parcel.'. $e->getMessage()]);
        }
    }

    /**
     * Display a listing of parcels for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $parcels = $user->parcels()->with(['office'])->latest()->paginate(15);
        
        return view('customer.parcels', compact('parcels'));
    }

    /**
     * Display the specified parcel.
     */
    public function show(Parcel $parcel)
    {
        // Ensure user can only view their own parcels
        if ($parcel->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to parcel.');
        }

        return view('customer.parcel-detail', compact('parcel'));
    }
} 