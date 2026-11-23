<?php
namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        return response()->json(['hasil' => Kamar::all()]);
    }

    public function tersedia(Request $r)
    {
        $v = $r->validate([
            'masuk'  => 'required|date',
            'keluar' => 'required|date|after:masuk',
            'kota'   => 'nullable|string',
        ]);
        $q = Kamar::tersedia($v['masuk'], $v['keluar']);
        if (!empty($v['kota'])) $q->where('kota', 'like', '%' . $v['kota'] . '%');
        return response()->json(['hasil' => $q->get()]);
    }
}
