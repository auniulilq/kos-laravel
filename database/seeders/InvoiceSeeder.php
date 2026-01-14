<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        if ($users->isEmpty()) {
            $users = User::factory()->count(3)->create();
        }

        // Gunakan status yang sesuai dengan enum di migration
        $statuses = ['draft', 'issued', 'paid', 'overdue'];

        foreach ($users as $user) {
            for ($m = 0; $m < 10; $m++) {
                $period = Carbon::now()->subMonths($m)->format('Y-m');
                $status = $statuses[array_rand($statuses)];
                // Hindari pelanggaran unique (user_id, period_month)
                Invoice::firstOrCreate(
                    ['user_id' => $user->id, 'period_month' => $period],
                    [
                        // Set due_date null agar aman terhadap view yang mengharapkan Carbon::format
                        'due_date' => null,
                        'status' => $status,
                        'total_amount' => rand(800_000, 2_000_000),
                        'notes' => $status === 'overdue' ? 'Jatuh tempo terlewati' : null,
                    ]
                );
            }
        }
    }
}