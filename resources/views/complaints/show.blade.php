@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-5">
    <div class="text-xs font-medium text-slate-400 flex items-center gap-2">
        <a href="/dashboard" class="hover:text-blue-600">Dashboard</a> <span>&rsaquo;</span>
        <a href="/complaints" class="hover:text-blue-600">Daftar Aduan</a> <span>&rsaquo;</span>
        <span class="text-slate-600 font-semibold">Detail Aduan #{{ $complaint->id }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 space-y-5">
                <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded text-slate-500">{{ $complaint->category->name }}</span>
                        <h1 class="text-xl font-bold text-slate-900 mt-2">{{ $complaint->title }}</h1>
                        <p class="text-xs text-slate-400 mt-1">Oleh: <span class="font-bold text-slate-600">{{ $complaint->user->name }}</span> • {{ $complaint->created_at->format('d M Y H:i') }}</p>
                    </div>
                    
                    @if(auth()->user()->role === 'admin')
                    <form action="/complaints/{{ $complaint->id }}" method="POST" class="flex gap-1.5 bg-slate-50 p-1.5 rounded-xl border border-slate-200">
                        @csrf
                        @method('PUT')
                        <select name="status" class="text-xs p-1.5 border border-slate-200 rounded-lg bg-white font-semibold">
                            <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="process" {{ $complaint->status == 'process' ? 'selected' : '' }}>Process</option>
                            <option value="done" {{ $complaint->status == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold">Update</button>
                    </form>
                    @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-slate-50 border">{{ $complaint->status }}</span>
                    @endif
                </div>

                <div class="space-y-1">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi Kejadian</h3>
                    <p class="text-sm text-slate-700 font-semibold">{{ $complaint->location ?? 'Tidak disertakan' }}</p>
                </div>

                <div class="space-y-1">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Isi Laporan Keluhan</h3>
                    <p class="text-sm text-slate-600 bg-slate-50/50 border p-4 rounded-xl leading-relaxed">{{ $complaint->description }}</p>
                </div>

                <div class="space-y-2 pt-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lampiran Bukti Foto</h3>
                    
                    {{-- FIX UTAMA: Cek kolom image_url, jika kosong otomatis cek kolom attachment sebagai cadangan data lama --}}
                    @if($complaint->image_url || isset($complaint->attachment))
                        <div class="mt-2 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 p-2 inline-block shadow-sm">
                            <img src="{{ asset($complaint->image_url ?? $complaint->attachment) }}" class="w-full max-w-md h-auto rounded-lg object-cover max-h-[320px]" alt="Bukti Nyata Pengaduan">
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Aduan ini tidak menyertakan berkas lampiran gambar.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 flex flex-col justify-between min-h-[420px]">
            <div class="w-full">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3">Kolom Tanggapan</h3>
                
                <div class="space-y-3.5 max-h-[300px] overflow-y-auto pr-1 mt-4">
                    @forelse($complaint->comments as $comment)
                    <div class="flex gap-2.5 items-start">
                        <div class="w-7 h-7 bg-blue-600 text-white rounded-full font-extrabold text-[10px] flex items-center justify-center uppercase shrink-0 shadow-sm">
                            {{ substr($comment->user->name, 0, 2) }}
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl rounded-tl-none border border-slate-100 flex-1 relative shadow-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-[11px] font-bold text-slate-900">{{ $comment->user->name }}</span>
                                <span class="text-[9px] font-bold uppercase px-1.5 rounded {{ $comment->user->role === 'admin' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">{{ $comment->user->role }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $comment->message }}</p>
                            
                            @if(auth()->user()->role === 'admin')
                            <form action="/comments/{{ $comment->id }}" method="POST" class="mt-1 text-right">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] text-red-500 hover:underline font-bold" onclick="return confirm('Hapus pesan ini?')">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic text-center py-6">Belum ada respon chat tanggapan resmi.</p>
                    @endforelse
                </div>
            </div>

            <form action="/comments" method="POST" class="mt-4 pt-3 border-t border-slate-100 flex gap-1.5">
                @csrf
                <input type="hidden" name="complaint_id" value="{{ $complaint->id }}">
                <input type="text" name="message" placeholder="Tulis balasan di sini..." required
                       class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-sm">Kirim</button>
            </form>
        </div>
    </div>
</div>
@endsection