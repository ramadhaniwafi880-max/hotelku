<?php
namespace App\Http\Controllers;

use App\Models\{Kamar, Pemesanan};
use Carbon\Carbon;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function store(Request $r)
    {
        $v = $r->validate([
            'kamar_id'       => 'required|exists:kamar,id',
            'nama_tamu'      => 'required|string',
            'email_tamu'     => 'required|email',
            'tanggal_masuk'  => 'required|date',
            'tanggal_keluar' => 'required|date|after:tanggal_masuk',
        ]);

        $kamar = Kamar::findOrFail($v['kamar_id']);
        if (!Kamar::tersedia($v['tanggal_masuk'], $v['tanggal_keluar'])->whereKey($kamar->id)->exists()) {
            return response()->json(['pesan' => 'Kamar tidak tersedia pada tanggal tersebut'], 409);
        }

        $malam = (int) abs(Carbon::parse($v['tanggal_masuk'])->diffInDays(Carbon::parse($v['tanggal_keluar'])));
        $v['total_harga'] = $malam * $kamar->harga_per_malam;

        return response()->json(['pemesanan' => Pemesanan::create($v)->load('kamar')], 201);
    }

    public function show($id)
    {
        return response()->json(['pemesanan' => Pemesanan::with('kamar')->findOrFail($id)]);
    }

    public function destroy($id)
    {
        $p = Pemesanan::findOrFail($id);
        $p->update(['status' => 'batal']);
        return response()->json(['pesan' => 'Pemesanan dibatalkan', 'pemesanan' => $p]);
    }
}
