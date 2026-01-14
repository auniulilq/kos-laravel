<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\RoomController as AdminRoom;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\ServiceRequestController as AdminService;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\PaymentController as UserPayment;
use App\Http\Controllers\User\ServiceRequestController as UserService;
use App\Http\Controllers\User\RoomController as UserRoom;
use App\Http\Controllers\User\MyBookingsController as UserMyBookings;
use App\Http\Controllers\Admin\ServiceOptionController as AdminServiceOption;
use App\Http\Controllers\User\AiChatController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Models\Category;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Payment;

// ==================== PUBLIC ROUTES ====================
Route::post('/payments/{id}/upload', [UserPayment::class, 'uploadProof'])->name('user.payments.upload');Route::post('/ai-assistant/chat', [App\Http\Controllers\User\PaymentController::class, 'chatAssistant'])->name('ai.chat');
Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
    // Route untuk detail pembayaran sewa
    Route::get('/payments/{id}', [App\Http\Controllers\User\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/upload', [App\Http\Controllers\User\PaymentController::class, 'uploadManual'])->name('payments.upload');
    
    // Route riwayat (jika belum ada)
    Route::get('/payments', [App\Http\Controllers\User\PaymentController::class, 'index'])->name('payments.index');
});
Route::get('/faq', function () {
    return view('faq');
})->name('faq');
Route::get('/', function () {
    return view('welcome', [
        'rooms' => Room::with('category')->where('status', 'empty')->latest()->paginate(6),
        'categories' => Category::all(),
        'facilities' => Facility::all(), 
        'totalRooms' => Room::count(),
        'occupiedRooms' => Room::where('status', 'occupied')->count(),
        'vacantRooms' => Room::where('status', 'empty')->count(),
    ]);
})->name('home');

// 1. Halaman Utama (Welcome)
Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('home');

// 2. Detail Kamar (Pastikan ID ada)
Route::get('/kamar/{id}', [App\Http\Controllers\RoomController::class, 'show'])->name('rooms.show');

// 3. Auth Routes (Dihasilkan oleh Laravel Breeze/UI)
Auth::routes();

Route::get('/home', function() {
    return redirect('/');
})->name('home');
Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index']);
// Sistem-only inquiry route
Route::post('/midtrans/notification', [\App\Http\Controllers\BookingController::class, 'handleNotification'])->name('midtrans.notification');
Route::post('/midtrans-callback', [\App\Http\Controllers\BookingController::class, 'handleNotification']);
Route::get('/kamar/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/', [WelcomeController::class, 'index'])->name('rooms.index');
// Tambahkan route dashboard global untuk Breeze
Route::get('/dashboard', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
});
// Authentication (gunakan Breeze)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
require __DIR__.'/auth.php';


// ==================== ADMIN ROUTES ====================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('rooms', AdminRoom::class);
    Route::patch('rooms/{room}/status', [AdminRoom::class, 'updateStatus'])->name('rooms.updateStatus');
    Route::resource('users', AdminUser::class);
    Route::patch('users/{user}/assign-room', [AdminUser::class, 'assignRoom'])->name('users.assignRoom');
    Route::get('payments', [AdminPayment::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPayment::class, 'show'])->name('payments.show');
    Route::post('payments/generate-bulk', [AdminPayment::class, 'generateBulk'])->name('payments.generateBulk');
    Route::patch('payments/{payment}/verify', [AdminPayment::class, 'verify'])->name('payments.verify');
    Route::patch('payments/{payment}/reject', [AdminPayment::class, 'reject'])->name('payments.reject');
    Route::post('payments/{payment}/wa-reminder', [AdminPayment::class, 'sendWaReminder'])->name('payments.sendWaReminder');
    Route::get('payments/{payment}/print', [AdminPayment::class, 'print'])->name('payments.print');
    Route::get('services', [AdminService::class, 'index'])->name('services.index');
    Route::get('services/{serviceRequest}', [AdminService::class, 'show'])->name('services.show');
    Route::patch('services/{serviceRequest}/approve', [AdminService::class, 'approve'])->name('services.approve');
    Route::patch('services/{serviceRequest}/reject', [AdminService::class, 'reject'])->name('services.reject');
    Route::patch('services/{serviceRequest}/complete', [AdminService::class, 'complete'])->name('services.complete');
    Route::post('services/{serviceRequest}/wa-update', [AdminService::class, 'sendWaUpdate'])->name('services.sendWaUpdate');
    Route::resource('service-options', AdminServiceOption::class);
    Route::get('reports', [AdminReport::class, 'index'])->name('reports.index');
    Route::get('reports/monthly', [AdminReport::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/export', [AdminReport::class, 'export'])->name('reports.export');
    Route::get('notifications/test', [AdminPayment::class, 'testForm'])->name('notifications.test');
    Route::post('notifications/test', [AdminPayment::class, 'testSend'])->name('notifications.test.send');
    Route::resource('categories', CategoryController::class);
});

// ==================== USER ROUTES ====================

Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('payments', [UserPayment::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [UserPayment::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/upload', [UserPayment::class, 'uploadProof'])->name('payments.upload');
    Route::get('payments/{payment}/download', [UserPayment::class, 'download'])->name('payments.download');
    Route::post('payments/{payment}/midtrans/token', [UserPayment::class, 'midtransToken'])->name('payments.midtrans.token');
    Route::get('services', [UserService::class, 'index'])->name('services.index');
    Route::get('services/create', [UserService::class, 'create'])->name('services.create');
    Route::post('services', [UserService::class, 'store'])->name('services.store');
    Route::get('services/{serviceRequest}', [UserService::class, 'show'])->name('services.show');
    Route::get('rooms', [UserRoom::class, 'index'])->name('rooms.index');
    Route::get('rooms/{room}', [UserRoom::class, 'show'])->name('rooms.show');
    Route::get('my-bookings', [UserMyBookings::class, 'index'])->name('my-bookings');
    Route::get('notifications', [UserDashboard::class, 'notifications'])->name('notifications');
    Route::patch('notifications/{notification}/read', [UserDashboard::class, 'markAsRead'])->name('notifications.read');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user/invoices', [InvoiceController::class, 'indexUser'])->name('user.invoices.index');
    Route::get('/admin/invoices', [InvoiceController::class, 'indexAdmin'])->name('admin.invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
});
Route::middleware(['auth'])->group(function () {
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});