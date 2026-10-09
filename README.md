# Daily Task – Daily Task (Kegiatan Harian)

A local Laravel app (no login) for managing daily activities in Indonesian.

- Dashboard with today's date/counts, nearest tasks, and "Tambah Kegiatan"
- Add/edit/delete and mark tasks done
- Reminders, calendar, search + filters
- History of finished tasks
- Settings (notifications, sound, default reminder, theme)
- Modern minimal blue UI

## Run (Windows)

1. Install prerequisites: PHP 8.3+, Composer, Node.js (for `npm`).
2. Open a terminal in this folder and run:

```bat
start.bat
```

or manually:

```bat
composer install --no-interaction
npm install
npm run build
php artisan key:generate
php artisan migrate --force
php artisan serve --port=8000
```

> `npm run build` wajib dijalankan sekali agar utilitas Tailwind CSS ter-compile
> (atau gunakan `npm run dev` + `php artisan serve` secara bersamaan saat development).

3. Open `http://127.0.0.1:8000/`.

## Tests

```sh
composer install
php artisan test
php artisan route:list
```

## Notes

- No auth is included.
- Database defaults to `database/database.sqlite` (config/database.php).
- All UI text is in Indonesian.
