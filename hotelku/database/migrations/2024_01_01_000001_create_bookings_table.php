<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $t) {
            $t->id();
            $t->string('hotel_code');          // A | B
            $t->unsignedBigInteger('hotel_room_id');
            $t->unsignedBigInteger('hotel_booking_id');
            $t->string('guest_name');
            $t->string('guest_email');
            $t->date('checkin');
            $t->date('checkout');
            $t->unsignedInteger('total_price');
            $t->string('status')->default('confirmed');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bookings'); }
};
