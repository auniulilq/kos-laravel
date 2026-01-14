<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;



class Booking extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id', 
    'room_id', 
    'duration_type', 
    'start_date', 
    'end_date', 
    'total_price', 
    'amount_paid',   // Nominal yang dibayar ke Midtrans
    'payment_method', // <--- Tambahkan ini (full/dp)
    'payment_status', // (pending, settlement, dp_paid, rejected)
    'status',         // (pending, active, completed, cancelled)
    'invoice_number', 
    'snap_token'
    // 'check_in_date' => 'date', 
    // 'check_out_date' => 'date',
];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relasi ke User (Penyewa)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kamar
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
public static function generateInvoiceNumber()
    {
        $date = Carbon::now()->format('Ymd');
        
        // Mengambil booking terakhir pada hari ini
        $lastBooking = self::whereDate('created_at', Carbon::today())
                            ->orderBy('id', 'desc')
                            ->first();

        if ($lastBooking && $lastBooking->invoice_number) {
            // Mengambil 4 digit terakhir dan menambahkannya 1
            $lastNumber = substr($lastBooking->invoice_number, -4);
            $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika booking pertama hari ini
            $nextNumber = '0001';
        }

        return 'INV-' . $date . '-' . $nextNumber;
    }
    
}