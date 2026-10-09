@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-bold text-biru-700">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola kegiatan harianmu tanpa login.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📅</span>
                <span class="text-sm text-gray-500">Hari Ini</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-biru-700">0</div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <span class="text-sm text-gray-500">Selesai</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-emerald-600">0</div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⏳</span>
                <span class="text-sm text-gray-500">Belum Selesai</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-amber-600">0</div>
        </div>
        <div class="card">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📊</span>
                <span class="text-sm text-gray-500">Total</span>
            </div>
            <div class="mt-3 text-3xl font-bold text-biru-700">0</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-lg">Kegiatan Hari Ini</h2>
            <span class="text-sm text-gray-500">{{ now()->translatedFormat('l, j F Y') }}</span>
        </div>
        <p class="text-sm text-gray-500">Belum ada kegiatan.</p>
        <a href="{{ route('tasks.create') }}" class="mt-3 text-biru-600 text-sm font-medium">Tambah kegiatan pertama</a>
    </div>
</div>
@endsection
