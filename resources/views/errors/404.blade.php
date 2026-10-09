@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="flex flex-col items-center justify-center text-center py-20">
    <span class="text-6xl">😕</span>
    <h1 class="text-2xl font-bold text-biru-700 mt-6">Halaman Tidak Ditemukan</h1>
    <p class="text-gray-500 mt-3 max-w-md">Maaf, halaman yang Anda cari belum tersedia atau telah dipindahkan.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-8">
        <span>←</span>
        <span>Kembali ke Dashboard</span>
    </a>
</div>
@endsection
