<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        if ($users->isEmpty()) {
            $users = User::factory()->count(3)->create();
        }

        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            for ($i = 1; $i <= 10; $i++) {
                $rooms->push(Room::create([
                    'room_number' => str_pad($i, 3, '0', STR_PAD_LEFT),
                    'price' => rand(600_000, 1_800_000),
                    'status' => 'empty',
                    'facilities' => ['wifi', 'ac'],
                    'description' => 'Kamar sample untuk seeding',
                    'area' => '3x4',
                    'electricity' => 'include',
                    'capacity' => rand(1, 3),
                ]));
            }
        }

        $statuses = ['pending', 'paid', 'verified'];
        $admin = User::where('role', 'admin')->first();

        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
            $room = $rooms->random();
            $status = $statuses[array_rand($statuses)];

            $amount = [
                'pending' => intval($room->price),
                'paid' => intval($room->price),
                'verified' => intval($room->price),
            ][$status];

            $paidAt = $status === 'paid' || $status === 'verified' ? Carbon::now()->subDays(rand(0, 10)) : null;
            $verifiedAt = $status === 'verified' ? Carbon::now()->subDays(rand(0, 5)) : null;
            $verifiedBy = $status === 'verified' && $admin ? $admin->id : null;

            Payment::create([
                'invoice_number' => Payment::generateInvoiceNumber(),
                'user_id' => $user->id,
                'room_id' => $room->id,
                'amount' => $amount,
                'month_year' => Carbon::now()->subMonths(rand(0, 2))->format('Y-m'),
                'status' => $status,
                'proof_image' => null,
                'paid_at' => $paidAt,
                'verified_at' => $verifiedAt,
                'verified_by' => $verifiedBy,
                'notes' => $status === 'pending' ? 'Menunggu verifikasi' : ($status === 'paid' ? 'Sudah dibayar, belum diverifikasi' : 'Pembayaran terverifikasi'),
                'snap_token' => null,
            ]);
        }
    }
}