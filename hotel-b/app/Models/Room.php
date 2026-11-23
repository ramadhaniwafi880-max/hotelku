<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_name', 'city', 'price_per_night', 'capacity'];

    public function bookings() { return $this->hasMany(Booking::class); }

    // Kamar tersedia = tidak ada booking confirmed yang tanggalnya bentrok
    public function scopeAvailable($q, $in, $out)
    {
        return $q->whereDoesntHave('bookings', fn ($b) => $b
            ->where('status', 'confirmed')
            ->where('checkin', '<', $out)
            ->where('checkout', '>', $in));
    }
}
