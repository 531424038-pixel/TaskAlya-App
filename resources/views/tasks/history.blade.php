@extends('layouts.app')

@section('title', 'Riwayat')

@section('content')
<div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-biru-700">Riwayat Kegiatan</h1>
        <span class="text-sm text-gray-500">{{ $tasks->count() }} kegiatan selesai</span>
    </div>

    @if($tasks->isEmpty())
        <div class="card">
            <p class="text-gray-500">Belum ada kegiatan yang selesai.</p>
        </div>
    @else
        <div class="card">
            <div class="space-y-3">
                @foreach($tasks as $task)
                    <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 dark:border-gray-800">
                        <span class="text-xl">✅</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-biru-700 truncate">{{ $task->name }}</p>
                            <p class="text-sm text-gray-500">
                                📅 {{ \Carbon\Carbon::parse($task->date)->translatedFormat('j F Y') }} |
                                🕐 {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }} |
                                🏷️ {{ $task->category }} |
                                ⚡ {{ $task->priority }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
