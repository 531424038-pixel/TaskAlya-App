@extends('layouts.app')

@section('title', $task->exists ? 'Edit Kegiatan' : 'Tambah Kegiatan')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-bold text-biru-700">{{ $task->exists ? 'Edit Kegiatan' : 'Tambah Kegiatan' }}</h1>
        <p class="text-sm text-gray-500 mt-1">Isi data kegiatan berikut ini.</p>
    </div>

    <form method="POST" action="{{ $task->exists ? route('tasks.update', $task) : route('tasks.store') }}" class="card">
        @csrf
        @if($task->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $task->name) }}" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                @error('name')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                <select id="category" name="category" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ old('category', $task->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioritas *</label>
                <select id="priority" name="priority" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                    <option value="">Pilih prioritas</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ old('priority', $task->priority) == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                    @endforeach
                </select>
                @error('priority')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reminder" class="block text-sm font-medium text-gray-700 mb-1">Waktu Pengingat *</label>
                <select id="reminder" name="reminder" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                    <option value="Tidak ada" {{ old('reminder', $task->reminder) == 'Tidak ada' ? 'selected' : '' }}>Tidak ada</option>
                    <option value="Saat kegiatan dimulai" {{ old('reminder', $task->reminder) == 'Saat kegiatan dimulai' ? 'selected' : '' }}>Saat kegiatan dimulai</option>
                    <option value="5 menit sebelumnya" {{ old('reminder', $task->reminder) == '5 menit sebelumnya' ? 'selected' : '' }}>5 menit sebelumnya</option>
                    <option value="10 menit sebelumnya" {{ old('reminder', $task->reminder) == '10 menit sebelumnya' ? 'selected' : '' }}>10 menit sebelumnya</option>
                    <option value="15 menit sebelumnya" {{ old('reminder', $task->reminder) == '15 menit sebelumnya' ? 'selected' : '' }}>15 menit sebelumnya</option>
                    <option value="30 menit sebelumnya" {{ old('reminder', $task->reminder) == '30 menit sebelumnya' ? 'selected' : '' }}>30 menit sebelumnya</option>
                    <option value="1 jam sebelumnya" {{ old('reminder', $task->reminder) == '1 jam sebelumnya' ? 'selected' : '' }}>1 jam sebelumnya</option>
                </select>
                @error('reminder')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                <input type="date" id="date" name="date" value="{{ old('date', $task->date ? $task->date->format('Y-m-d') : now()->format('Y-m-d')) }}" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                @error('date')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai *</label>
                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $task->start_time ? $task->start_time->format('H:i') : '') }}" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                @error('start_time')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai *</label>
                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $task->end_time ? $task->end_time->format('H:i') : '') }}" required class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">
                @error('end_time')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-biru-200 focus:border-biru-400 dark:border-gray-700 dark:bg-gray-800">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                {{ $task->exists ? 'Simpan Perubahan' : 'Simpan Kegiatan' }}
            </button>
        </div>
    </form>
</div>
@endsection
