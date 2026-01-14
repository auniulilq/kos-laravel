<?php
// app/Http/Controllers/User/DashboardController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $room = $user->room;

     $stats = [
    'pending_payments' => Payment::where('user_id', $user->id)
        ->where('status', 'pending')
        ->count(),

    // Menghitung semua status yang dianggap "Lunas"
    'verified_payments' => Payment::where('user_id', $user->id)
        ->whereIn(\DB::raw('LOWER(status)'), ['success', 'approved', 'verified', 'completed', 'lunas'])
        ->count(),

    'pending_services' => ServiceRequest::where('user_id', $user->id)
        ->whereIn(\DB::raw('LOWER(status)'), ['pending', 'approved', 'in_progress'])
        ->count(),

    // Menghitung layanan yang SUDAH selesai
    'completed_services' => ServiceRequest::where('user_id', $user->id)
        ->whereIn('status', ['completed', 'successed']) // Tambahkan status selesai lainnya
        ->count(),

    // Total tagihan dan layanan
    'total_payments' => Payment::where('user_id', $user->id)->count(),
    'total_services' => ServiceRequest::where('user_id', $user->id)->count(),
];

        $recent_payments = Payment::where('user_id', $user->id)
            ->with('room')
            ->latest()
            ->take(5)
            ->get();

        $active_services = ServiceRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved', 'in_progress'])
            ->latest()
            ->get();

        $notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        // Informasi kamar terkini
        $room_info = null;
        if ($room) {
            $room_info = [
                'room_number' => $room->room_number,
                'type' => $room->category->name ?? 'Standard',
                'price' => $room->formatted_price,
                'status' => $room->status,
                'facilities' => is_array($room->facilities) ? $room->facilities : [],
                'description' => $room->description,
            ];
        }

        // Pembayaran yang akan datang (berdasarkan month_year)
        $upcoming_payments = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('month_year', '>=', now()->format('Y-m'))
            ->orderBy('month_year')
            ->take(3)
            ->get()
            ->map(function ($payment) {
                return [
                    'description' => 'Pembayaran sewa kamar ' . $payment->room->room_number,
                    'amount' => $payment->formatted_amount,
                    'due_date' => Carbon::parse($payment->month_year)->endOfMonth()->format('d M Y'),
                ];
            });

        // Status layanan terbaru
       $service = ServiceRequest::where('user_id', $user->id)
    ->latest()
    ->first();

$latest_service_status = null;
if ($service) {
    $latest_service_status = [
        // Sesuaikan 'title' dengan nama kolom di database kamu (misal: service_name atau category)
        'title' => $service->service_name ?? $service->title ?? 'Layanan Kamar', 
        'status' => $service->status,
        'description' => $service->description ?? 'Tidak ada deskripsi',
        'created_at' => $service->created_at->format('d M Y'),
        'updated_at' => $service->updated_at->format('d M Y'),
        'status_color' => match($service->status) {
            'pending' => '#f59e0b',     // Kuning
            'approved' => '#3b82f6',    // Biru
            'in_progress' => '#8b5cf6', // Ungu
            'completed' => '#10b981',   // Hijau
            'rejected' => '#ef4444',    // Merah
            default => '#64748b'
        }
    ];
}
        // Booking aktif
        $active_booking = Booking::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('room')
            ->latest()
            ->first();

$active_services_list = ServiceRequest::where('user_id', $user->id)
    ->whereIn('status', ['pending', 'approved', 'in_progress'])
    ->latest()
    ->get()
    ->map(function ($service) {
        return [
            // Cek apakah kolomnya bernama 'service_name' atau 'title'
            'title' => $service->service_name ?? $service->title ?? 'Layanan Kamar',
            'status' => $service->status,
            'date' => $service->created_at->format('d M Y'),
            'status_color' => match($service->status) {
                'pending' => '#f59e0b',     // Kuning
                'approved' => '#3b82f6',    // Biru
                'in_progress' => '#8b5cf6', // Ungu
                default => '#64748b'
            }
        ];
    });

// Jangan lupa kirim ke view
return view('user.dashboard', compact(
    'user', 'room', 'stats', 'recent_payments', 
    'active_services_list', // Ganti variabel ini
    'notifications', 'room_info', 'upcoming_payments', 
    'latest_service_status', 'active_booking'
));    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('user.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }
   public function chatAssistant(\Illuminate\Http\Request $request)
{
    try {
        $pesan = strtolower($request->message);
        $user = auth()->user();

        // Cek jika user login
        if (!$user) {
            return response()->json(['reply' => 'Silakan login terlebih dahulu.']);
        }

        if (str_contains($pesan, 'tagihan') || str_contains($pesan, 'status')) {
            // Pastikan model Payment diimport di atas: use App\Models\Payment;
            $tagihan = \App\Models\Payment::where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->first();

            if ($tagihan) {
                // Gunakan format manual jika formatted_amount error
                $harga = number_format($tagihan->amount, 0, ',', '.');
                return response()->json([
                    'reply' => "Halo {$user->name}, ada tagihan **#{$tagihan->invoice_number}** sebesar **Rp {$harga}** yang belum dibayar. Segera dilunasi ya!"
                ]);
            }
            return response()->json(['reply' => "Hebat! Semua tagihan kamu sudah lunas."]);
        }

        return response()->json(['reply' => "Saya asisten pembayaran. Tanya saya tentang 'status tagihan' atau 'cara bayar'."]);

    } catch (\Exception $e) {
        // Cek log di storage/logs/laravel.log untuk detail error
        \Log::error("Chat Error: " . $e->getMessage());
        return response()->json(['reply' => 'Maaf, sistem asisten sedang gangguan. Coba lagi nanti.'], 500);
    }
}
}
