@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                {{ auth()->user()->role === 'admin' ? 'Semua Aduan Masuk' : 'Aduan Saya' }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">Pantau status laporan keluhan yang ada di sistem SIKELUH.</p>
        </div>
        
        @if(auth()->user()->role !== 'admin')
        <a href="/complaints/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-md shadow-blue-600/20 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Aduan
        </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        @forelse($complaints as $complaint)
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 border-b border-slate-100 last:border-0 hover:bg-slate-50 transition">
            
            <div class="flex items-start sm:items-center gap-4 mb-4 sm:mb-0">
                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-inner
                    {{ $complaint->status == 'pending' ? 'bg-orange-50 text-orange-500' : ($complaint->status == 'process' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-500') }}">
                    @if($complaint->status == 'pending')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($complaint->status == 'process')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    @endif
                </div>
                
                <div>
                    <h3 class="text-base font-bold text-slate-900 mb-0.5">{{ $complaint->title }}</h3>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                            {{ $complaint->category->name ?? 'Fasilitas' }}
                        </span>
                        <span>•</span>
                        <span>{{ $complaint->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                    {{ $complaint->status == 'pending' ? 'bg-orange-50 text-orange-600 border border-orange-200' : ($complaint->status == 'process' ? 'bg-blue-50 text-blue-600 border border-blue-200' : 'bg-green-50 text-green-600 border border-green-200') }}">
                    {{ $complaint->status }}
                </span>

                <div class="w-px h-6 bg-slate-200 mx-1"></div>

                <a href="/complaints/{{ $complaint->id }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </a>

                <a href="/complaints/{{ $complaint->id }}/edit" class="p-2 text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition" title="Edit Aduan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center p-12 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Belum ada aduan</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm">Anda belum mengirimkan laporan keluhan apa pun ke sistem. Klik tombol "Buat Aduan" untuk memulai.</p>
        </div>
        @endforelse

    </div>
</div>
@endsection