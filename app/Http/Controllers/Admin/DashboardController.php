<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\Notification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Existing Stats
        $now = Carbon::now();
        $stats = [
            'total_rooms' => Room::count(),
            'empty_rooms' => Room::where('status', 'empty')->count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'maintenance_rooms' => Room::where('status', 'maintenance')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'pending_services' => ServiceRequest::where('status', 'pending')->count(),
            'monthly_income' => Payment::where('status', 'verified')
                ->whereYear('paid_at', $now->year)
                ->whereMonth('paid_at', $now->month)
                ->sum('amount'),
        ];

        // Chart Data: Monthly Income (Last 12 Months)
        $monthlyIncomeData = [
            'labels' => [],
            'data' => [],
        ];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            $income = Payment::where('status', 'verified')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('amount');
            $monthlyIncomeData['labels'][] = $monthName;
            $monthlyIncomeData['data'][] = $income;
        }

        // Chart Data: Occupancy Rate (Last 30 Days)
        $occupancyData = [
            'labels' => [],
            'data' => [],
        ];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('d M');
            // Simplified: using current occupancy count as daily value
            $occupiedCount = Room::where('status', 'occupied')->count();
            $occupancyData['labels'][] = $dateString;
            $occupancyData['data'][] = $occupiedCount;
        }

        // Chart Data: Payment Status (Verified, Pending, Rejected)
        $verifiedPayments = Payment::where('status', 'verified')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $rejectedPayments = Payment::where('status', 'rejected')->count();

        $paymentSuccessData = [
            'labels' => ['Verified', 'Pending', 'Rejected'],
            'data' => [$verifiedPayments, $pendingPayments, $rejectedPayments],
        ];

        // Recent Activities
        $recent_payments = Payment::with(['user', 'room'])
            ->orderByDesc('paid_at')
            ->latest('id')
            ->take(5)
            ->get();

        $recent_services = ServiceRequest::with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();

        // Notifikasi real-time untuk admin
        $notifications = Notification::where('type', 'admin')
            ->orWhere('type', 'all')
            ->latest()
            ->take(10)
            ->get();

        // Notifikasi yang belum dibaca
        $unread_notifications = Notification::where('is_read', false)
            ->where(function($query) {
                $query->where('type', 'admin')
                      ->orWhere('type', 'all');
            })
            ->count();

        // Aktivitas terbaru dari user
        $recent_user_activities = collect()
            ->merge(Payment::where('status', 'pending')
                ->with(['user', 'room'])
                ->latest()
                ->take(3)
                ->get()
                ->map(function($payment) {
                    return [
                        'type' => 'payment',
                        'message' => 'Pembayaran baru dari '.$payment->user->name.' - Kamar '.$payment->room->room_number,
                        'time' => $payment->created_at,
                        'url' => route('admin.payments.show', $payment)
                    ];
                }))
            ->merge(ServiceRequest::where('status', 'pending')
                ->with(['user', 'room'])
                ->latest()
                ->take(3)
                ->get()
                ->map(function($service) {
                    return [
                        'type' => 'service',
                        'message' => 'Permintaan layanan baru dari '.$service->user->name.' - Kamar '.$service->room->room_number,
                        'time' => $service->created_at,
                        'url' => route('admin.services.show', $service)
                    ];
                }))
            ->sortByDesc('time')
            ->take(5)
            ->values();

        return view('admin.dashboard', compact(
            'stats', 
            'recent_payments', 
            'recent_services', 
            'monthlyIncomeData', 
            'occupancyData', 
            'paymentSuccessData',
            'notifications',
            'unread_notifications',
            'recent_user_activities'
        ));
    }
}
