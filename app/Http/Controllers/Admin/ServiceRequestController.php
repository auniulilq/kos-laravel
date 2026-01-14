<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Payment;
use App\Services\WhatsAppService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['user', 'room', 'payment']);

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

        return view('admin.services.index', compact('services'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load('user', 'room', 'payment');
        return view('admin.services.show', compact('serviceRequest'));
    }

    public function approve(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ]);

        $serviceRequest->update([
            'status' => 'approved',
            'price' => $validated['price'],
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Create payment for this service
        $payment = Payment::create([
            'invoice_number' => Payment::generateInvoiceNumber(),
            'user_id' => $serviceRequest->user_id,
            'room_id' => $serviceRequest->room_id,
            'amount' => $validated['price'],
            'type' => 'service',
            'month_year' => now()->format('Y-m'),
            'status' => 'pending',
        ]);

        $serviceRequest->update(['payment_id' => $payment->id]);

        // Notify user
        app(NotificationService::class)->serviceRequestStatusUpdated($serviceRequest, 'pending');

        // WhatsApp update (respect opt-in)
        $wa = new WhatsAppService();
        $user = $serviceRequest->user;
        if (!empty($user->phone) && ($user->whatsapp_opt_in ?? false)) {
            $wa->sendServiceUpdate($user, $serviceRequest, 'approved');
        }

        return back()->with('success', 'Layanan berhasil disetujui');
    }

    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $oldStatus = $serviceRequest->status;
        $serviceRequest->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Event akan otomatis dispatch melalui model boot method
        // WhatsApp update (respect opt-in)
        $wa = new WhatsAppService();
        $user = $serviceRequest->user;
        if (!empty($user->phone) && ($user->whatsapp_opt_in ?? false)) {
            $wa->sendServiceUpdate($user, $serviceRequest, 'rejected');
        }

        return back()->with('success', 'Layanan ditolak');
    }

    public function complete(ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->payment_status !== 'paid') {
            return back()->with('error', 'Layanan belum dibayar');
        }

        $serviceRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Event akan otomatis dispatch melalui model boot method
        // WhatsApp update (respect opt-in)
        $wa = new WhatsAppService();
        $user = $serviceRequest->user;
        if (!empty($user->phone) && ($user->whatsapp_opt_in ?? false)) {
            $wa->sendServiceUpdate($user, $serviceRequest, 'completed');
        }

        return back()->with('success', 'Layanan ditandai selesai');
    }

    public function sendWaUpdate(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load('user');
        $user = $serviceRequest->user;
        if (!$user || empty($user->phone)) {
            return back()->with('error', 'Nomor telepon tidak tersedia');
        }
        if (!($user->whatsapp_opt_in ?? false)) {
            return back()->with('error', 'User tidak mengizinkan WhatsApp');
        }

        $wa = new WhatsAppService();
        $wa->sendServiceUpdate($user, $serviceRequest, $serviceRequest->status);

        return back()->with('success', 'WA Update layanan dikirim');
    }
}
