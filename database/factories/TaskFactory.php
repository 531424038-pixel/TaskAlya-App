<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $today = now()->format('Y-m-d');

        return [
            'name' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(),
            'date' => fake()->optional(0.7)->dateTimeBetween('+0 days', '+30 days')->format('Y-m-d'),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
            'category' => fake()->randomElement(['Kuliah', 'Tugas', 'Pekerjaan', 'Pribadi', 'Olahraga', 'Lainnya']),
            'priority' => fake()->randomElement(['Rendah', 'Sedang', 'Tinggi']),
            'reminder' => fake()->randomElement(['Tidak ada', 'Saat kegiatan dimulai', '5 menit sebelumnya', '10 menit sebelumnya', '15 menit sebelumnya', '30 menit sebelumnya', '1 jam sebelumnya']),
            'reminder_at' => null,
            'status' => fake()->randomElement(['belum selesai', 'sedang dikerjakan', 'selesai']),
        ];
    }

    public function upcoming(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i'),
            'end_time' => now()->addHour()->format('H:i'),
        ]);
    }
}
