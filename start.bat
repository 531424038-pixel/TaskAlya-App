@echo off
REM Daily Task – Daily Task (Kegiatan Harian) start script for Windows
setlocal

set "PROJECT_DIR=%~dp0"
cd /d "%PROJECT_DIR%"

echo Checking prerequisites...

WHERE /Q composer
if %errorlevel% neq 0 (
    echo [ERROR] Composer not found. Please install Composer first:
    echo   https://getcomposer.org/download/
    pause
    exit /b 1
)

WHERE /Q php
if %errorlevel% neq 0 (
    echo [WARN] PHP not found. Make sure php.exe is in your PATH.
    echo        You can still run 'composer install' manually.
)

echo Installing PHP dependencies...
composer install --no-interaction --prefer-dist

if not exist ".env" (
    echo .env belum ada - menyalin dari .env.example...
    copy /Y .env.example .env
)

echo Generating application key...
php artisan key:generate --force

if not exist "database\database.sqlite" (
    echo Membuat database SQLite kosong...
    type nul > "database\database.sqlite"
)

echo Running database migrations...
php artisan migrate --force

if %errorlevel% neq 0 (
    echo [ERROR] Migration failed.
    pause
    exit /b 1
)

echo Dependencies installed and database migrated.
WHERE /Q npm
if %errorlevel% equ 0 (
    echo Installing JS dependencies and building assets...
    call npm install
    call npm run build
) else (
    echo [WARN] npm tidak ditemukan. Jalankan "npm install" lalu "npm run build" agar tampilan Tailwind aktif.
)

echo Starting development server at http://127.0.0.1:8000
echo Press Ctrl+C to stop.
php artisan serve --host=127.0.0.1 --port=8000 --no-reload

endlocal
