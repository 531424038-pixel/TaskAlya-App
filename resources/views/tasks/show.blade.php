@extends('layouts.app')

@section('title', 'Detail Kegiatan')

@section('content')
<div class="flex flex-col gap-6 max-w-3xl">
    <a href="{{ route('tasks.index') }}" class="text-sm text-biru-600 hover:underline">← Kembali ke daftar kegiatan</a>

    <div class="card">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-biru-700">{{ $task->name }}</h1>
                @if($task->description)
                    <p class="text-gray-600 mt-2">{{ $task->description }}</p>
                @endif
            </div>
            <span class="status-badge {{ $task->status === 'selesai' ? 'status-selesai' : ($task->status === 'sedang dikerjakan' ? 'status-sedang-dikerjakan' : 'status-belum-selesai') }}">
                {{ ucwords(str_replace(' ', '-', $task->status)) }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
            <div class="flex flex-col gap-3">
                <h2 class="text-sm font-semibold text-gray-500">Waktu</h2>
                <div class="flex flex-wrap gap-4 text-sm">
                    <span>📅 {{ \Carbon\Carbon::parse($task->date)->translatedFormat('j F Y') }}</span>
                    <span>🕐 {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-3">
                <h2 class="text-sm font-semibold text-gray-500">Kategori & Prioritas</h2>
                <div class="flex flex-wrap gap-4 text-sm">
                    <span class="category-badge">{{ $task->category }}</span>
                    <span class="priority-badge {{ 'priority-' . $task->priority }}">{{ $task->priority }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mt-6">
            @if($task->status !== 'selesai')
                <form method="POST" action="{{ route('tasks.markDone', $task) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary flex items-center gap-2">
                        <span class="text-lg">✅</span>
                        <span>Tandai Selesai</span>
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('tasks.markUndone', $task) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline flex items-center gap-2 text-emerald-600">
                        <span class="text-lg">🔄</span>
                        <span>Batalkan Selesai</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline">Edit</a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus kegiatan ini?')">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
