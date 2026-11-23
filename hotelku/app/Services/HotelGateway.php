<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Penghubung HotelKu -> Hotel A & B.
 * Format respons kedua hotel berbeda, di sini diseragamkan.
 */
class HotelGateway
{
    private static function url(string $c): string
    {
        return rtrim(config("hotels.$c.url"), '/');
    }

    public static function codes(): array
    {
        return array_keys(config('hotels'));
    }

    /** Ambil kamar dari semua hotel (opsional filter tanggal/kota). Hotel yang mati dilewati. */
    public static function rooms(?string $in = null, ?string $out = null, ?string $city = null): array
    {
        $all = [];
        foreach (self::codes() as $c) {
            try {
                $all = array_merge($all, self::roomsOf($c, $in, $out, $city));
            } catch (\Throwable $e) {
                // hotel tidak bisa dihubungi -> lewati
            }
        }
        return $all;
    }

    private static function roomsOf(string $c, ?string $in, ?string $out, ?string $city): array
    {
        $name = config("hotels.$c.name");

        if ($c === 'A') {
            $res = $in
                ? Http::acceptJson()->get(self::url('A') . '/rooms/available', array_filter(['checkin' => $in, 'checkout' => $out, 'city' => $city]))
                : Http::acceptJson()->get(self::url('A') . '/rooms');
            return collect($res->throw()->json('data'))
                ->when(!$in && $city, fn ($c2) => $c2->filter(fn ($r) => stripos($r['city'], $city) !== false))
                ->map(fn ($r) => [
                    'hotel' => 'A', 'hotel_name' => $name, 'room_id' => $r['id'],
                    'name' => $r['room_name'], 'city' => $r['city'],
                    'price_per_night' => $r['price_per_night'], 'capacity' => $r['capacity'],
                ])->values()->all();
        }

        // Hotel B (format Indonesia)
        $res = $in
            ? Http::acceptJson()->get(self::url('B') . '/kamar/tersedia', array_filter(['masuk' => $in, 'keluar' => $out, 'kota' => $city]))
            : Http::acceptJson()->get(self::url('B') . '/kamar');
        return collect($res->throw()->json('hasil'))
            ->when(!$in && $city, fn ($c2) => $c2->filter(fn ($r) => stripos($r['kota'], $city) !== false))
            ->map(fn ($r) => [
                'hotel' => 'B', 'hotel_name' => $name, 'room_id' => $r['id'],
                'name' => $r['nama_kamar'], 'city' => $r['kota'],
                'price_per_night' => $r['harga_per_malam'], 'capacity' => $r['kapasitas'],
            ])->values()->all();
    }

    /** Buat booking di hotel tujuan. Lempar RequestException jika ditolak. */
    public static function book(string $c, array $d): array
    {
        if ($c === 'A') {
            $r = Http::acceptJson()->post(self::url('A') . '/bookings', [
                'room_id' => $d['room_id'], 'guest_name' => $d['guest_name'], 'guest_email' => $d['guest_email'],
                'checkin' => $d['checkin'], 'checkout' => $d['checkout'],
            ])->throw()->json();
            return ['id' => $r['id'], 'total_price' => $r['total_price']];
        }

        $r = Http::acceptJson()->post(self::url('B') . '/pemesanan', [
            'kamar_id' => $d['room_id'], 'nama_tamu' => $d['guest_name'], 'email_tamu' => $d['guest_email'],
            'tanggal_masuk' => $d['checkin'], 'tanggal_keluar' => $d['checkout'],
        ])->throw()->json('pemesanan');
        return ['id' => $r['id'], 'total_price' => $r['total_harga']];
    }

    public static function cancel(string $c, int $hotelBookingId): void
    {
        $path = $c === 'A' ? "/bookings/$hotelBookingId" : "/pemesanan/$hotelBookingId";
        Http::acceptJson()->delete(self::url($c) . $path)->throw();
    }
}
