<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        if ($users->isEmpty()) {
            // fallback: buat beberapa user jika belum ada
            $users = User::factory()->count(3)->create();
        }

        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            // jika belum ada, buat 10 kamar sederhana
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

        $durationTypes = ['weekly', 'monthly', 'yearly'];

        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
            $room = $rooms->random();
            $durationType = $durationTypes[array_rand($durationTypes)];

            $start = Carbon::now()->subDays(rand(0, 30));
            switch ($durationType) {
                case 'weekly':
                    $weeks = rand(1, 4);
                    $end = (clone $start)->addWeeks($weeks);
                    $total = intval(($room->price / 4) * $weeks);
                    break;
                case 'yearly':
                    $years = 1;
                    $end = (clone $start)->addYear($years);
                    $total = intval($room->price * 12 * $years);
                    break;
                case 'monthly':
                default:
                    $months = rand(1, 3);
                    $end = (clone $start)->addMonths($months);
                    $total = intval($room->price * $months);
                    break;
            }

            $paymentMethod = rand(0, 1) ? 'full' : 'dp';
            $amountPaid = $paymentMethod === 'dp' ? intval($total * 0.3) : $total;

            Booking::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'duration_type' => $durationType,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'total_price' => $total,
                'amount_paid' => $amountPaid,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'status' => 'pending',
                'invoice_number' => Booking::generateInvoiceNumber(),
                'snap_token' => null,
            ]);
        }
    }
}