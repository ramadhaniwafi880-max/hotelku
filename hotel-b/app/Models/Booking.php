<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['room_id', 'guest_name', 'guest_email', 'checkin', 'checkout', 'total_price', 'status'];

    public function room() { return $this->belongsTo(Room::class); }
}
