<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $t) {
            $t->id();
            $t->string('room_name');
            $t->string('city');
            $t->unsignedInteger('price_per_night');
            $t->unsignedInteger('capacity');
            $t->timestamps();
        });
        Schema::create('bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('room_id')->constrained('rooms');
            $t->string('guest_name');
            $t->string('guest_email');
            $t->date('checkin');
            $t->date('checkout');
            $t->unsignedInteger('total_price');
            $t->string('status')->default('confirmed'); // confirmed | cancelled
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('rooms');
    }
};
