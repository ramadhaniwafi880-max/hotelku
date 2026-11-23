<?php
namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Room::all()]);
    }

    public function available(Request $r)
    {
        $v = $r->validate([
            'checkin'  => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'city'     => 'nullable|string',
        ]);
        $q = Room::available($v['checkin'], $v['checkout']);
        if (!empty($v['city'])) $q->where('city', 'like', '%' . $v['city'] . '%');
        return response()->json(['data' => $q->get()]);
    }
}
