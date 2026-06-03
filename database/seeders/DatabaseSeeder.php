<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Putrajaya pilot area (Presint 9 vicinity).
        $base = ['lat' => 2.9264, 'lng' => 101.6964];

        $aminah = User::create([
            'name' => 'Aminah (Demo)',
            'phone' => '0123456789',
            'phone_verified_at' => now(),
            'is_verified' => true,
            'verified_at' => now(),
            'home_lat' => 2.9270,
            'home_lng' => 101.6970,
            'address_label' => 'Presint 9, Putrajaya',
        ]);

        $hafiz = User::create([
            'name' => 'Hafiz (Demo)',
            'phone' => '0198765432',
            'phone_verified_at' => now(),
            'is_verified' => false,
            'home_lat' => 2.9240,
            'home_lng' => 101.6940,
            'address_label' => 'Presint 8, Putrajaya',
        ]);

        $siti = User::create([
            'name' => 'Siti (Demo)',
            'phone' => '0112223344',
            'phone_verified_at' => now(),
            'is_verified' => true,
            'verified_at' => now(),
            'home_lat' => 2.9300,
            'home_lng' => 101.6920,
            'address_label' => 'Presint 11, Putrajaya',
        ]);

        $jitter = fn (float $v) => $v + (mt_rand(-25, 25) / 10000);

        $posts = [
            ['user' => $hafiz, 'type' => 'sos', 'title' => 'Kehabisan susu bayi, perlu segera', 'body' => 'Kedai berdekatan tutup. Sesiapa ada lebihan susu formula step 1?', 'severity' => 'high'],
            ['user' => $siti, 'type' => 'donate', 'title' => 'Almari pakaian kayu untuk diberi', 'body' => 'Masih elok, sekadar nak lapang ruang. Sesiapa berminat boleh ambil.', 'meta' => ['item_name' => 'Almari Pakaian', 'condition' => 'Baik', 'qty' => 1]],
            ['user' => $aminah, 'type' => 'donate', 'title' => 'Buku sekolah rendah & baju kanak-kanak', 'body' => 'Anak dah besar. Boleh derma kepada yang memerlukan.', 'meta' => ['item_name' => 'Buku & Baju Kanak-kanak', 'condition' => 'Seperti Baru', 'qty' => 1]],
            ['user' => $hafiz, 'type' => 'tool', 'title' => 'Pinjam mesin gerudi (drill)', 'body' => 'Nak gantung rak dinding. Boleh pinjam sehari?', 'meta' => ['tool_name' => 'Mesin Gerudi Bosch', 'available_until' => 'Hujung minggu']],
            ['user' => $aminah, 'type' => 'mobility', 'title' => 'Tumpang ke Stesen MRT Putrajaya Sentral', 'body' => 'Setiap pagi 7:45am, ada 2 tempat kosong.', 'meta' => ['origin' => 'Presint 9', 'destination' => 'Putrajaya Sentral', 'depart_time' => '7:45 pagi', 'seats' => 2]],
            ['user' => $siti, 'type' => 'group_buy', 'title' => 'Servis & cuci aircond rumah', 'body' => 'Buat servis aircond kawasan Putrajaya. Harga jiran istimewa.', 'price' => 60.00, 'meta' => ['item' => 'Servis Aircond', 'deadline' => 'Tempahan hujung minggu']],
            ['user' => $siti, 'type' => 'sos', 'title' => 'Air paip tersumbat, perlu bantuan', 'body' => 'Ada sesiapa pandai paip?', 'severity' => 'medium'],
            ['user' => $hafiz, 'type' => 'group_buy', 'title' => 'Jual kuih raya homemade', 'body' => 'Tempahan kuih raya rumah. Boleh COD sekawasan.', 'price' => 35.00, 'meta' => ['item' => 'Kuih Raya (balang)', 'deadline' => 'Tempahan 3 hari awal']],
        ];

        foreach ($posts as $p) {
            $user = $p['user'];
            Post::create([
                'user_id' => $user->id,
                'type' => $p['type'],
                'title' => $p['title'],
                'body' => $p['body'] ?? null,
                'severity' => $p['severity'] ?? null,
                'price' => $p['price'] ?? null,
                'meta' => $p['meta'] ?? null,
                'lat' => $jitter($base['lat']),
                'lng' => $jitter($base['lng']),
                'radius_km' => 3,
                'status' => 'open',
                'expires_at' => $p['type'] === 'sos' ? now()->addHours(12) : null,
            ]);
        }
    }
}
