<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\HotelGateway;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelKuController extends Controller
{
    public function hotels()
    {
        return response()->json(['data' => HotelGateway::rooms()]);
    }

    public function search(Request $r)
    {
        $v = $r->validate([
            'checkin'  => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'city'     => 'nullable|string',
        ]);
        return response()->json(['data' => HotelGateway::rooms($v['checkin'], $v['checkout'], $v['city'] ?? null)]);
    }

    public function store(Request $r)
    {
        $v = $r->validate([
            'hotel'       => ['required', Rule::in(HotelGateway::codes())],
            'room_id'     => 'required|integer',
            'guest_name'  => 'required|string',
            'guest_email' => 'required|email',
            'checkin'     => 'required|date',
            'checkout'    => 'required|date|after:checkin',
        ]);

        try {
            $res = HotelGateway::book($v['hotel'], $v);
        } catch (RequestException $e) {
            return response()->json([
                'message' => 'Hotel menolak pemesanan',
                'detail'  => $e->response->json(),
            ], $e->response->status());
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Hotel tidak dapat dihubungi'], 502);
        }

        $b = Booking::create([
            'hotel_code' => $v['hotel'], 'hotel_room_id' => $v['room_id'], 'hotel_booking_id' => $res['id'],
            'guest_name' => $v['guest_name'], 'guest_email' => $v['guest_email'],
            'checkin' => $v['checkin'], 'checkout' => $v['checkout'], 'total_price' => $res['total_price'],
        ]);
        return response()->json($b, 201);
    }

    public function show($id)
    {
        return response()->json(Booking::findOrFail($id));
    }

    public function destroy($id)
    {
        $b = Booking::findOrFail($id);
        try {
            HotelGateway::cancel($b->hotel_code, $b->hotel_booking_id);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal membatalkan di hotel'], 502);
        }
        $b->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Booking dibatalkan', 'booking' => $b]);
    }
}
