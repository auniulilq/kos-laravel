<?php
// ============================================================
// app/Console/Commands/GenerateMonthlyPayments.php (CRON JOB)
// ============================================================

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use App\Models\Payment;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class GenerateMonthlyPayments extends Command
{
    protected $signature = 'payments:generate-monthly';
    protected $description = 'Generate monthly rent payments automatically';

    public function handle()
    {
        $monthYear = Carbon::now()->format('Y-m');
        $occupiedRooms = Room::where('status', 'occupied')->with('user')->get();
        
        $count = 0;
        $whatsapp = new WhatsAppService();

        foreach ($occupiedRooms as $room) {
            $exists = Payment::where('user_id', $room->user_id)
                ->where('room_id', $room->id)
                ->where('month_year', $monthYear)
                ->where('type', 'rent')
                ->exists();

            if (!$exists) {
                $payment = Payment::create([
                    'invoice_number' => Payment::generateInvoiceNumber(),
                    'user_id' => $room->user_id,
                    'room_id' => $room->id,
                    'amount' => $room->price,
                    'type' => 'rent',
                    'month_year' => $monthYear,
                    'status' => 'pending',
                ]);

                // Send WhatsApp notification
                $whatsapp->sendPaymentReminder($room->user, $payment);

                $count++;
            }
        }

        $this->info("Successfully generated {$count} payments for {$monthYear}");
        
        return 0;
    }
}