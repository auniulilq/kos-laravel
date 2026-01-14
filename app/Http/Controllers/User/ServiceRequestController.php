<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\ServiceOption;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\Payment;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceRequest::where('user_id', auth()->id())
            ->with('room', 'payment', 'serviceOption');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where('description', 'like', "%$q%");
        }

        $services = $query->latest()
            ->paginate(6)
            ->appends($request->query());

        return view('user.services.index', compact('services'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if (!$user->room) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Anda belum memiliki kamar');
        }

        $options = ServiceOption::active()->orderBy('service_type')->orderBy('name')->get();
        return view('user.services.create', compact('options'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:laundry,blanket,repair,other',
            'service_option_id' => 'nullable|exists:service_options,id',
            'quantity' => 'nullable|integer|min:1',
            'description' => 'required|string',
        ]);

        $user = auth()->user();

        if (!$user->room) {
            return back()->with('error', 'Anda belum memiliki kamar');
        }

        // Hitung harga berdasarkan opsi jika ada
        $price = null;
        $option = null;
        if (!empty($validated['service_option_id'])) {
            $option = ServiceOption::active()->find($validated['service_option_id']);
            if (!$option) {
                return back()->with('error', 'Opsi layanan tidak tersedia')->withInput();
            }
            // Validasi type konsisten
            if ($option->service_type !== $validated['service_type']) {
                return back()->with('error', 'Opsi tidak sesuai dengan jenis layanan')->withInput();
            }

            // Pricing logic
            if ($option->pricing_type === 'fixed') {
                $price = $option->price;
                $validated['quantity'] = 1;
            } elseif ($option->pricing_type === 'per_unit') {
                $qty = $validated['quantity'] ?? $option->min_qty;
                // Clamp qty
                if ($qty < $option->min_qty) { $qty = $option->min_qty; }
                if ($option->max_qty && $qty > $option->max_qty) { $qty = $option->max_qty; }
                $validated['quantity'] = $qty;
                $price = ($option->price ?? 0) * $qty;
            } else { // quote
                $price = null; // akan ditentukan admin
                $validated['quantity'] = 1;
            }
        }

        $validated['user_id'] = $user->id;
        $validated['room_id'] = $user->room->id;
        $validated['status'] = 'pending';
        $validated['price'] = $price;

        $service = ServiceRequest::create($validated);

        // Kirim notifikasi ke admin dan user
        app(NotificationService::class)->serviceRequestCreated($service);

        return redirect()->route('user.services.index')
            ->with('success', 'Permintaan layanan berhasil diajukan');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        // Check ownership
        if ($serviceRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $serviceRequest->load('room', 'payment', 'serviceOption');
        return view('user.services.show', compact('serviceRequest'));
    }
    // app/Models/ServiceRequest.php
public function getPaymentStatusAttribute()
{
    return $this->payment ? $this->payment->status : 'no_payment';
}
}
