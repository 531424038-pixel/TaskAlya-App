@extends('layouts.app')

@section('title', 'Kegiatan')

@section('content')
<div class="flex flex-col gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-2xl font-bold text-biru-700">Daftar Kegiatan</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <span>+</span>
            <span>Tambah Kegiatan</span>
        </a>
    </div>

    <div class="card">
        <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label for="search" class="sr-only">Cari kegiatan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>
                    <input
                        id="search"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Cari nama kegiatan..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-biru-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    >
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <select name="status" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>{{ ucwords(str_replace(' ', '', $status)) }}</option>
                    @endforeach
                </select>
                <select name="priority" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Prioritas</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ ($filters['priority'] ?? '') === $priority ? 'selected' : '' }}>{{ $priority }}</option>
                    @endforeach
                </select>
                <select name="category" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ ($filters['category'] ?? '') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 bg-biru-600 text-white rounded-lg text-sm font-semibold hover:bg-biru-700 transition">Filter</button>
                @if($filters)
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($tasks as $task)
            <div class="card rounded-xl p-5 flex flex-col gap-3 {{ $task->status === 'sedang dikerjakan' ? 'border-biru-200 bg-biru-50/40' : '' }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-biru-700 truncate">{{ $task->name }}</h3>
                        @if($task->description)
                            <p class="text-sm text-gray-500 line-clamp-2 mt-1">{{ $task->description }}</p>
                        @endif
                    </div>
                    <span class="status-badge {{ $task->status === 'selesai' ? 'status-selesai' : ($task->status === 'sedang dikerjakan' ? 'status-sedang-dikerjakan' : 'status-belum-selesai') }}">
                        {{ ucwords(str_replace(' ', '-', $task->status)) }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1">🕐 {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}</span>
                    <span class="flex items-center gap-1">📅 {{ \Carbon\Carbon::parse($task->date)->format('j F Y') }}</span>
                    <span class="category-badge">{{ $task->category }}</span>
                    <span class="priority-badge {{ 'priority-' . $task->priority }}">{{ $task->priority }}</span>
                </div>

                <div class="flex items-center gap-2 mt-auto">
                    <a href="{{ route('tasks.show', $task) }}" class="text-sm text-biru-600 hover:text-biru-800 font-medium">Detail</a>
                    <a href="{{ route('tasks.edit', $task) }}" class="text-sm text-gray-500 hover:text-biru-600 font-medium">Edit</a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-gray-400 hover:text-danger transition" onclick="return confirm('Hapus kegiatan ini?')">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-3 card">
                <p class="text-gray-500">Belum ada kegiatan.</p>
                <a href="{{ route('tasks.create') }}" class="mt-3 text-biru-600 text-sm font-medium">Buat kegiatan pertama</a>
            </div>
        @endforelse
    </div>

    @if($tasks->hasPages())
        <div class="pagination mt-4">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection
