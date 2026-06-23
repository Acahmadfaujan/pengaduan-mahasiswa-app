@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Ringkasan</h1>
        <p class="text-sm text-slate-500">Pantau perkembangan laporan pengaduan mahasiswa secara real-time.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Aduan</span>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalAduan }}</p>
            <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden"><div class="bg-slate-500 w-full h-full"></div></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending</span>
            <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $pending }}</p>
            <div class="w-full bg-amber-100 h-1 rounded-full mt-3 overflow-hidden"><div class="bg-amber-500 w-1/3 h-full"></div></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Diproses</span>
            <p class="text-3xl font-extrabold text-blue-600 mt-2">{{ $diproses }}</p>
            <div class="w-full bg-blue-100 h-1 rounded-full mt-3 overflow-hidden"><div class="bg-blue-500 w-1/2 h-full"></div></div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Selesai</span>
            <p class="text-3xl font-extrabold text-green-600 mt-2">{{ $selesai }}</p>
            <div class="w-full bg-green-100 h-1 rounded-full mt-3 overflow-hidden"><div class="bg-green-50 w-full h-full"></div></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 space-y-4">
        <div class="flex justify-between items-center border-b border-slate-100 pb-4">
            <h2 class="font-bold text-slate-900">Aktivitas Laporan Terbaru</h2>
            <a href="/complaints" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($aduanTerbaru as $aduan)
            <div class="flex items-center justify-between py-3.5 first:pt-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-sm shadow-sm">📢</div>
                    <div>
                        <a href="/complaints/{{ $aduan->id }}" class="font-bold text-slate-900 text-sm hover:text-blue-600 transition block">{{ $aduan->title }}</a>
                        <p class="text-[11px] text-slate-400 mt-0.5">Pelapor: <span class="font-semibold text-slate-600">{{ $aduan->user->name }}</span> • Kategori: {{ $aduan->category->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <span class="text-xs text-slate-400 font-medium">{{ $aduan->created_at->format('d M Y') }}</span>
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase border
                        {{ $aduan->status == 'done' ? 'bg-green-50 text-green-600 border-green-100' : '' }}
                        {{ $aduan->status == 'process' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                        {{ $aduan->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}">
                        {{ $aduan->status }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 italic text-center py-4">Belum ada rekaman laporan masuk saat ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection