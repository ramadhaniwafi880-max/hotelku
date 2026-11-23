<?php
namespace App\Http\Controllers;

use App\Models\{Booking, Room};
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $r)
    {
        $v = $r->validate([
            'room_id'     => 'required|exists:rooms,id',
            'guest_name'  => 'required|string',
            'guest_email' => 'required|email',
            'checkin'     => 'required|date',
            'checkout'    => 'required|date|after:checkin',
        ]);

        $room = Room::findOrFail($v['room_id']);
        if (!Room::available($v['checkin'], $v['checkout'])->whereKey($room->id)->exists()) {
            return response()->json(['message' => 'Kamar tidak tersedia pada tanggal tersebut'], 409);
        }

        $nights = (int) abs(Carbon::parse($v['checkin'])->diffInDays(Carbon::parse($v['checkout'])));
        $v['total_price'] = $nights * $room->price_per_night;

        return response()->json(Booking::create($v)->load('room'), 201);
    }

    public function show($id)
    {
        return response()->json(Booking::with('room')->findOrFail($id));
    }

    public function destroy($id)
    {
        $b = Booking::findOrFail($id);
        $b->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Booking dibatalkan', 'booking' => $b]);
    }
}
