# Hasil pemeriksaan Task-app

## Temuan berbasis bukti

1. `.env` dan `.env.example` memakai `SESSION_DRIVER=database` dan `CACHE_STORE=database`. Ini membuat request bergantung pada tabel `sessions` dan `cache`; bila database yang dipakai tidak memiliki tabel tersebut atau koneksi database gagal, request bisa berakhir HTTP 500. Untuk proyek lokal SQLite ini keduanya diubah ke `file`, sehingga session/cache tidak bergantung pada tabel database. Database dan isi tabel tidak diubah.
2. `app/Exceptions/Handler.php` mencoba merender view dengan nama `errors::404`, sedangkan view proyek berada di `resources/views/errors/404.blade.php` (nama view `errors.404`). Path view diperbaiki. Catatan: aplikasi Laravel 13 pada proyek ini menggunakan konfigurasi exception di `bootstrap/app.php`; file Handler ini tidak tampak didaftarkan di bootstrap, sehingga perubahan ini bukan bukti penyebab 500 pada request saat ini.
3. Log `storage/logs/laravel.log` yang disertakan hanya menunjukkan error lama: missing APP_KEY (8 Okt), `.env` tidak ditemukan (9 Okt 18:54), file SQLite tidak ditemukan (9 Okt 18:58), dan `could not find driver` (9 Okt 19:07). Tidak ada entri error lebih baru dalam log yang disertakan. Karena itu log tersebut tidak membuktikan penyebab aktif saat ini; kondisi runtime sekarang harus diuji ulang di Windows/XAMPP.

## Pemeriksaan yang berhasil

- `php artisan route:list --except-vendor` berhasil dan menampilkan 15 route.
- Lint sintaks seluruh file PHP di `app`, `bootstrap`, `config`, `database`, `routes`, `resources`, dan `tests` tidak melaporkan kesalahan sintaks.

## Pemeriksaan yang terhalang

- `php artisan test` dan `php artisan view:cache` tidak dapat berjalan di lingkungan pemeriksaan ini karena PHP CLI yang tersedia tidak memiliki ekstensi `dom`, `mbstring`, dan `xmlwriter` (view cache berhenti pada `Class "DOMDocument" not found`).
- Pengujian HTTP nyata terhadap halaman yang membaca SQLite tidak dapat dinyatakan berhasil dari lingkungan ini karena PHP CLI di lingkungan pemeriksaan tidak memuat ekstensi PDO SQLite. Jalankan verifikasi akhir memakai PHP XAMPP yang Anda gunakan untuk server.

## Langkah verifikasi di Windows

Dari PowerShell pada `D:\Project\Task-app`:

```powershell
where.exe php
php -m | findstr /I "PDO sqlite pdo_sqlite dom mbstring xmlwriter"
php artisan optimize:clear
php artisan route:list
php artisan migrate:status
php artisan serve --host=127.0.0.1 --port=8000
```

Buka `http://127.0.0.1:8000`. Jika masih 500, muat ulang sekali lalu periksa bagian paling bawah `storage/logs/laravel.log`; gunakan timestamp paling baru, bukan error lama yang sudah disebutkan. Jangan jalankan `migrate:fresh` atau menghapus database karena dapat menghilangkan data.
