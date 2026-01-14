<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Tambahkan ini di atas

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- LOGIKA UNTUK NGROK AGAR ASSET (CSS/JS) TIDAK RUSAK ---
        if (config('app.env') !== 'local' || str_contains(request()->getHost(), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }
        // ---------------------------------------------------------

        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $unreadCount = 0;
                $activities = collect();

                if ($user->isAdmin()) {
                    // ADMIN: Melihat pembayaran yang perlu diverifikasi
                    $unreadCount = \App\Models\Payment::where('status', 'pending')->count();
                    
                    $activities = \App\Models\Payment::with(['user'])
                                    ->latest()
                                    ->take(5)
                                    ->get()
                                    ->map(function($p) {
                                        return [
                                            'message' => "Pembayaran baru dari " . ($p->user->name ?? 'User'),
                                            'time' => $p->created_at,
                                            'url' => route('admin.payments.index') 
                                        ];
                                    });
                } else {
                    // USER: Hanya melihat notifikasi miliknya sendiri
                    $unreadCount = \App\Models\Payment::where('user_id', $user->id)
                                    ->whereIn('status', ['success', 'failed'])
                                    ->where('updated_at', '>=', now()->subDays(1))
                                    ->count();

                    $activities = \App\Models\Payment::where('user_id', $user->id)
                                    ->latest()
                                    ->take(5)
                                    ->get()
                                    ->map(function($p) {
                                        return [
                                            'message' => "Status pembayaran #" . $p->id . " adalah " . $p->status,
                                            'time' => $p->updated_at,
                                            'url' => route('user.payments.index')
                                        ];
                                    });
                }

                $view->with([
                    'unread_notifications' => $unreadCount,
                    'recent_user_activities' => $activities
                ]);
            }
        });
    }
}