<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceRequest;
use App\Models\ServiceOption;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        if ($users->isEmpty()) {
            $users = User::factory()->count(3)->create();
        }

        $rooms = Room::all();
        if ($rooms->isEmpty()) {
            // Minimal room agar relasi valid
            for ($i = 1; $i <= 5; $i++) {
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

        // Pastikan ada beberapa opsi layanan
        $types = ['laundry','blanket','repair','other'];
        $options = ServiceOption::active()->get();
        if ($options->isEmpty()) {
            foreach ($types as $t) {
                $options->push(ServiceOption::create([
                    'service_type' => $t,
                    'name' => ucfirst($t).' Basic',
                    'pricing_type' => 'fixed',
                    'unit_name' => 'unit',
                    'price' => rand(20_000, 80_000),
                    'min_qty' => 1,
                    'max_qty' => 10,
                    'is_active' => true,
                ]));
            }
        }

        $statuses = ['pending','approved','in_progress','completed','rejected'];

        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
            $room = $rooms->random();
            $opt = $options->random();
            $qty = rand($opt->min_qty ?? 1, max($opt->max_qty ?? 5, 1));
            $pricePerUnit = $opt->price ?? rand(20_000, 50_000);
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = in_array($status, ['completed']) && rand(0,1) ? 'paid' : 'unpaid';
            $paymentId = null;

            if ($paymentStatus === 'paid') {
                $payment = Payment::where('user_id', $user->id)->inRandomOrder()->first();
                $paymentId = $payment?->id;
            }

            ServiceRequest::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'service_type' => $opt->service_type,
                'service_option_id' => $opt->id,
                'quantity' => $qty,
                'description' => 'Permintaan layanan '.ucfirst($opt->service_type).' sebanyak '.$qty.' '.$opt->unit_name,
                'price' => $pricePerUnit * $qty,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_id' => $paymentId,
                'admin_notes' => $status === 'rejected' ? 'Tidak memenuhi syarat' : null,
                'completed_at' => $status === 'completed' ? Carbon::now()->subDays(rand(0,5)) : null,
            ]);
        }
    }
}