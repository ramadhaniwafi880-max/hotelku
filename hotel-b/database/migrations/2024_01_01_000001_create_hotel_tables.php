<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $t) {
            $t->id();
            $t->string('nama_kamar');
            $t->string('kota');
            $t->unsignedInteger('harga_per_malam');
            $t->unsignedInteger('kapasitas');
            $t->timestamps();
        });
        Schema::create('pemesanan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('kamar_id')->constrained('kamar');
            $t->string('nama_tamu');
            $t->string('email_tamu');
            $t->date('tanggal_masuk');
            $t->date('tanggal_keluar');
            $t->unsignedInteger('total_harga');
            $t->string('status')->default('aktif'); // aktif | batal
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
        Schema::dropIfExists('kamar');
    }
};
