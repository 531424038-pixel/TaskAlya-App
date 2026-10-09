<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('category', ['Kuliah', 'Tugas', 'Pekerjaan', 'Pribadi', 'Olahraga', 'Lainnya']);
            $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi']);
            $table->enum('reminder', ['Tidak ada', 'Saat kegiatan dimulai', '5 menit sebelumnya', '10 menit sebelumnya', '15 menit sebelumnya', '30 menit sebelumnya', '1 jam sebelumnya']);
            $table->timestamp('reminder_at')->nullable();
            $table->enum('status', ['belum selesai', 'sedang dikerjakan', 'selesai'])->default('belum selesai');
            $table->timestamps();

            $table->index(['date', 'start_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
