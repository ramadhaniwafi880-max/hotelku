<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $fillable = ['kamar_id', 'nama_tamu', 'email_tamu', 'tanggal_masuk', 'tanggal_keluar', 'total_harga', 'status'];

    public function kamar() { return $this->belongsTo(Kamar::class); }
}
