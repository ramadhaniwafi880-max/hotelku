<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $fillable = ['nama_kamar', 'kota', 'harga_per_malam', 'kapasitas'];

    public function pemesanan() { return $this->hasMany(Pemesanan::class); }

    public function scopeTersedia($q, $masuk, $keluar)
    {
        return $q->whereDoesntHave('pemesanan', fn ($b) => $b
            ->where('status', 'aktif')
            ->where('tanggal_masuk', '<', $keluar)
            ->where('tanggal_keluar', '>', $masuk));
    }
}
