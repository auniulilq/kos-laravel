<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\Room;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Kirim notifikasi ke user tertentu
     */
    public function sendToUser($userId, $type, $title, $message, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * Kirim notifikasi ke semua admin
     */
    public function sendToAdmins($type, $title, $message, $data = [])
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            $this->sendToUser($admin->id, $type, $title, $message, $data);
        }
        
        return $admins->count();
    }

    /**
     * Kirim notifikasi ke semua user (kecuali admin)
     */
    public function sendToUsers($type, $title, $message, $data = [])
    {
        $users = User::where('role', '!=', 'admin')->get();
        
        foreach ($users as $user) {
            $this->sendToUser($user->id, $type, $title, $message, $data);
        }
        
        return $users->count();
    }

    /**
     * Notifikasi pembayaran baru
     */
    public function paymentCreated(Payment $payment)
    {
        // Notifikasi ke user
        $this->sendToUser(
            $payment->user_id,
            'payment_created',
            'Pembayaran Baru',
            'Tagihan ' . $payment->description . ' sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah dibuat.',
            [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'description' => $payment->description,
                'due_date' => Carbon::parse($payment->month_year)->endOfMonth()->format('d/m/Y'),
            ]
        );

        // Notifikasi ke admin
        $this->sendToAdmins(
            'payment_created',
            'Pembayaran Baru',
            'User ' . $payment->user->name . ' memiliki tagihan baru sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . '.',
            [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
                'user_name' => $payment->user->name,
                'amount' => $payment->amount,
                'description' => $payment->description,
            ]
        );
    }

    /**
     * Notifikasi pembayaran berhasil
     */
    public function paymentCompleted(Payment $payment)
    {
        // Notifikasi ke user
        $this->sendToUser(
            $payment->user_id,
            'payment_completed',
            'Pembayaran Berhasil',
            'Pembayaran ' . $payment->description . ' sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah berhasil.',
            [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'description' => $payment->description,
                'payment_method' => $payment->payment_method,
            ]
        );

        // Notifikasi ke admin
        $this->sendToAdmins(
            'payment_completed',
            'Pembayaran Berhasil',
            'User ' . $payment->user->name . ' telah berhasil membayar tagihan sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . '.',
            [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
                'user_name' => $payment->user->name,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
            ]
        );
    }

    /**
     * Notifikasi service request baru
     */
    public function serviceRequestCreated(ServiceRequest $serviceRequest)
    {
        // Notifikasi ke user
        $this->sendToUser(
            $serviceRequest->user_id,
            'service_created',
            'Permintaan Layanan Baru',
            'Permintaan layanan ' . $serviceRequest->title . ' telah dibuat dan sedang diproses.',
            [
                'service_id' => $serviceRequest->id,
                'title' => $serviceRequest->title,
                'description' => $serviceRequest->description,
                'priority' => $serviceRequest->priority,
            ]
        );

        // Notifikasi ke admin
        $this->sendToAdmins(
            'service_created',
            'Permintaan Layanan Baru',
            'User ' . $serviceRequest->user->name . ' mengajukan permintaan layanan: ' . $serviceRequest->title . '.',
            [
                'service_id' => $serviceRequest->id,
                'user_id' => $serviceRequest->user_id,
                'user_name' => $serviceRequest->user->name,
                'title' => $serviceRequest->title,
                'priority' => $serviceRequest->priority,
            ]
        );
    }

    /**
     * Notifikasi status service request berubah
     */
    public function serviceRequestStatusUpdated(ServiceRequest $serviceRequest, $oldStatus)
    {
        // Notifikasi ke user
        $this->sendToUser(
            $serviceRequest->user_id,
            'service_status_updated',
            'Status Layanan Diperbarui',
            'Status permintaan layanan ' . $serviceRequest->title . ' berubah dari ' . $oldStatus . ' menjadi ' . $serviceRequest->status . '.',
            [
                'service_id' => $serviceRequest->id,
                'title' => $serviceRequest->title,
                'old_status' => $oldStatus,
                'new_status' => $serviceRequest->status,
                'notes' => $serviceRequest->notes,
            ]
        );

        // Notifikasi ke admin (jika user yang mengubah)
        if (auth()->user() && auth()->user()->id === $serviceRequest->user_id) {
            $this->sendToAdmins(
                'service_status_updated',
                'Status Layanan Diperbarui',
                'User ' . $serviceRequest->user->name . ' memperbarui status layanan ' . $serviceRequest->title . ' menjadi ' . $serviceRequest->status . '.',
                [
                    'service_id' => $serviceRequest->id,
                    'user_id' => $serviceRequest->user_id,
                    'user_name' => $serviceRequest->user->name,
                    'title' => $serviceRequest->title,
                    'new_status' => $serviceRequest->status,
                ]
            );
        }
    }

    /**
     * Notifikasi kamar baru atau perubahan status
     */
    public function roomStatusUpdated(Room $room, $oldStatus = null)
    {
        if ($room->user_id) {
            $message = $oldStatus 
                ? 'Status kamar ' . $room->room_number . ' berubah dari ' . $oldStatus . ' menjadi ' . $room->status . '.'
                : 'Anda telah ditugaskan ke kamar ' . $room->room_number . '.';

            $this->sendToUser(
                $room->user_id,
                'room_status_updated',
                'Status Kamar Diperbarui',
                $message,
                [
                    'room_id' => $room->id,
                    'room_number' => $room->room_number,
                    'old_status' => $oldStatus,
                    'new_status' => $room->status,
                ]
            );
        }

        // Notifikasi ke admin
        $this->sendToAdmins(
            'room_status_updated',
            'Status Kamar Diperbarui',
            'Status kamar ' . $room->room_number . ' telah diperbarui menjadi ' . $room->status . '.',
            [
                'room_id' => $room->id,
                'room_number' => $room->room_number,
                'new_status' => $room->status,
                'user_id' => $room->user_id,
            ]
        );
    }

    /**
     * Tandai notifikasi sebagai dibaca
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    /**
     * Tandai semua notifikasi sebagai dibaca
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Hapus notifikasi
     */
    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->delete();
            return true;
        }
        return false;
    }
}