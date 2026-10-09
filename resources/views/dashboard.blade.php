@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-biru-700">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola kegiatan harianmu tanpa login.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <span>+</span>
            <span>Tambah Kegiatan</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📅</span>
                <span class="text-sm text-gray-500">Hari Ini</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-biru-700">{{ $tasksToday->count() }}</div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <span class="text-sm text-gray-500">Selesai</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-emerald-600">{{ $doneToday }}</div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⏳</span>
                <span class="text-sm text-gray-500">Belum Selesai</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-amber-600">{{ $todoToday }}</div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📊</span>
                <span class="text-sm text-gray-500">Total</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-biru-700">{{ $totalAll }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-lg">Kegiatan Hari Ini</h2>
                <span class="text-sm text-gray-500">{{ $today }}</span>
            </div>

            @if($tasksToday->isEmpty())
                <div class="text-center py-10 text-gray-500">
                    <p class="text-lg">Belum ada kegiatan hari ini.</p>
                    <a href="{{ route('tasks.create') }}" class="mt-3 text-biru-600 text-sm font-medium underline underline-offset-2">Tambah kegiatan pertama</a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($tasksToday as $task)
                        <div class="flex flex-col gap-3 rounded-lg border border-gray-100 p-4 transition hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/40 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="font-semibold text-biru-700">{{ $task->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    ⏰ {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }} |
                                    🏷️ {{ $task->category }} |
                                    ⚡ {{ $task->priority }}
                                </p>
                            </div>
                            <div class="flex shrink-0 gap-2">
                                <form method="POST" action="{{ route('tasks.markDone', $task) }}">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">Selesai</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="xl:col-span-1 card">
            <h2 class="font-semibold text-lg">Kegiatan Terdekat</h2>
            <p class="text-sm text-gray-500 mb-4">Kegiatan berikutnya yang akan masuk jam.</p>

            @if($dueSoon->isEmpty())
                <p class="text-sm text-gray-500">Belum ada kegiatan yang akan datang.</p>
            @else
                <div class="space-y-3">
                    @foreach($dueSoon as $task)
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-biru-600">{{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium truncate text-biru-700">{{ $task->name }}</p>
                                <p class="text-xs text-gray-500">{{ $task->category }} / {{ $task->priority }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
