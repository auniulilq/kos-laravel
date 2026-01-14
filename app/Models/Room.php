<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\RoomStatusUpdated;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'category_id',
        'price',
        'status',
        'facilities',
        'description',
        'image',
        'user_id',
        'area',        // Tambah ini
    'electricity', // Tambah ini
    'capacity'
    ];

    protected $casts = [
        'facilities' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    // Scopes
    public function scopeEmpty($query)
    {
        return $query->where('status', 'empty');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'empty' => '<span class="badge bg-success">Kosong</span>',
            'occupied' => '<span class="badge bg-danger">Terisi</span>',
            'maintenance' => '<span class="badge bg-warning">Perbaikan</span>',
        };
    }
    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

    /**
     * Boot method untuk model event
     */
    protected static function boot()
    {
        parent::boot();

        // Dispatch event saat status berubah
        static::updated(function ($room) {
            if ($room->isDirty('status')) {
                $oldStatus = $room->getOriginal('status');
                event(new RoomStatusUpdated($room, $oldStatus));
            }
        });
    }
    // Di dalam model Room
public function getFacilitiesAttribute($value)
{
    if (is_null($value) || $value === "") return [];
    
    // Jika sudah di-cast oleh $casts, $value mungkin sudah jadi array
    if (is_array($value)) return $value;

    return json_decode($value, true) ?: explode(',', $value);
}
}