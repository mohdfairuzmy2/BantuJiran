# BantuJiran

Platform komuniti digital hiper-lokal Malaysia. Menghubungkan jiran dalam radius geografi yang spesifik untuk mencetuskan bantuan fizikal secara terus (Peer-to-Peer).

## Teknologi
- **Backend:** Laravel 11 (PHP 8.2+, diuji pada PHP 8.4)
- **Pangkalan Data:** MySQL dengan fungsi spatial (`ST_Distance_Sphere`) untuk pemadanan radius masa nyata
- **Frontend:** Blade + Bootstrap 5 (mobile-first), Leaflet + OpenStreetMap untuk peta interaktif

## Modul Utama
1. **Mohon Bantuan** — hebahkan keperluan mendesak kepada jiran berdekatan
2. **Derma Barang** — iklan barang tidak digunakan untuk diberi; jiran lain boleh lihat & mohon
3. **Perkongsian Peralatan** — pinjam barang jarang guna
4. **Mobiliti & Tumpangan** — carpool jarak dekat
5. **Pengiklanan Perkhidmatan/Barangan** — iklan perkhidmatan & barangan jualan kepada jiran sekawasan

> Nota: Modul **Keselamatan & Pemantauan** dikeluarkan buat masa ini atas pertimbangan keselamatan.

## Sistem Kepercayaan
- **Log masuk telefon** (Pengguna Asas) — masukkan nombor telefon (& nama jika pendaftaran baharu). Tiada gateway SMS dalam mod pembangunan.
- **MyDigital ID (e-KYC)** — tahap "Disahkan" untuk lencana kepercayaan yang lebih tinggi. Integrasi OIDC sebenar belum disambung; lapisan (`is_verified`, butang stub) telah disediakan.
- **Skor Kepercayaan** — dikira daripada ulasan selepas bantuan selesai; lencana jiran dipaparkan.
- **Privasi** — nombor telefon & alamat tepat disembunyikan; komunikasi melalui sembang dalaman.

## Keperluan
- PHP 8.2+ dengan sambungan biasa Laravel (mbstring, pdo_mysql, dll.)
- Composer 2
- MySQL 8+ (perlu sokongan fungsi spatial `ST_Distance_Sphere`)
- Node.js 18+ & npm (pilihan — hanya jika anda mahu bina aset Vite/Tailwind)

## Persediaan Setempat

```bash
# 1. Klon repo
git clone https://github.com/mohdfairuzmy2/BantuJiran.git
cd BantuJiran

# 2. Pasang kebergantungan PHP
composer install

# 3. Sediakan fail persekitaran & kunci aplikasi
cp .env.example .env
php artisan key:generate

# 4. Cipta pangkalan data & pengguna
mysql -u root -p -e "
CREATE DATABASE IF NOT EXISTS bantujiran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'bantujiran'@'localhost' IDENTIFIED BY 'bantujiran123';
GRANT ALL PRIVILEGES ON bantujiran.* TO 'bantujiran'@'localhost';
FLUSH PRIVILEGES;"

# 5. Migrasi + data demo
php artisan migrate --seed

# 6. (Pilihan) Bina aset frontend — UI utama guna CDN, jadi langkah ini tidak wajib
npm install
npm run build

# 7. Jalankan pelayan
php artisan serve
```

Buka `http://localhost:8000` dan log masuk dengan akaun demo di bawah.

> Pastikan tetapan `DB_*` dalam `.env` sepadan: lalai projek menggunakan
> `DB_DATABASE=bantujiran`, `DB_USERNAME=bantujiran`, `DB_PASSWORD=bantujiran123`.

## Akaun Demo (kawasan Putrajaya)
| Nama | Telefon | Tahap |
|------|---------|-------|
| Aminah | `0123456789` | Disahkan (MyDigital ID) |
| Siti | `0112223344` | Disahkan |
| Hafiz | `0198765432` | Asas |

Log masuk: masukkan nombor telefon yang berdaftar untuk terus masuk (mod pembangunan).

## Struktur Penting
- `app/Models/Post.php` — `scopeWithinRadius()` menggunakan `ST_Distance_Sphere` pada lajur `POINT` terjana (SRID 4326) dengan indeks spatial.
- `app/Http/Controllers/` — Auth, Post, Response, Chat, Review, Profile, Push, Admin.
- `resources/views/feed/index.blade.php` — paparan hibrid Peta/Senarai.
