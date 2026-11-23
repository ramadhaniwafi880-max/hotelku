<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['hotel_code', 'hotel_room_id', 'hotel_booking_id', 'guest_name', 'guest_email', 'checkin', 'checkout', 'total_price', 'status'];
}
