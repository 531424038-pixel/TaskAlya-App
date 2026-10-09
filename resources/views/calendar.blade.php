@extends('layouts.app')

@section('title', 'Kalender')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-biru-700">Kalender Kegiatan</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $month }}
                @if($today)
                    <span class="text-sm text-gray-400">| Hari ini: {{ $today }}</span>
                @endif
            </p>
        </div>
    </div>

    <div class="card">
        <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-gray-500 mb-2">
            @foreach($weekDays as $day)
                <div class="py-2">{{ $day }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-1">
            @foreach($weeks as $week)
                @foreach($week as $day)
                    <div
                        class="relative min-h-[120px] p-2 rounded-lg border transition hover:bg-gray-50 {{ $day['isCurrentMonth'] ? 'border-gray-200' : 'border-transparent opacity-40' }} {{ $day['isToday'] ? 'ring-2 ring-biru-400' : '' }}"
                    >
                        <span class="text-sm font-medium {{ $day['isToday'] ? 'text-biru-700 font-bold' : '' }}">
                            {{ \Carbon\Carbon::parse($day['date'])->format('j') }}
                        </span>

                        @if($tasks->has($day['date']))
                            <div class="mt-2 space-y-1 min-h-[40px]">
                                @foreach($tasks->get($day['date']) as $task)
                                    <div class="text-xs px-2 py-1 rounded bg-biru-100 text-biru-700 truncate {{ $task->status === 'sedang dikerjakan' ? 'bg-amber-100 text-amber-700' : '' }} {{ $task->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                        {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} {{ $task->name }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="card">
        <h2 class="text-sm font-semibold text-gray-500 mb-3">Ringkasan Kegiatan Berdasarkan Tanggal</h2>
        <div class="flex flex-col gap-2">
            @foreach($activities as $date => $summary)
                <div class="flex justify-between text-sm py-1 border-b border-gray-100 last:border-0 dark:border-gray-800">
                    <span>{{ \Carbon\Carbon::parse($date)->translatedFormat('j F') }}</span>
                    <span class="flex items-center gap-2">
                        <span class="text-biru-600 font-medium">{{ $summary['todos'] ?? 0 }}</span>
                        <span class="text-xs text-gray-400">belum selesai</span>
                        @if(isset($summary['done']))
                            <span class="text-xs text-emerald-600">• {{ $summary['done'] }} selesai</span>
                        @endif
                    </span>
                </div>
            @endforeach
            @if(count($activities) === 0)
                <p class="text-sm text-gray-500">Belum ada aktivitas kegiatan.</p>
            @endif
        </div>
    </div>
</div>
@endsection
