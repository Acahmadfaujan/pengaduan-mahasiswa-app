@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Profil Pengguna</h1>
    
    <div class="flex items-center gap-6 mb-8 border-b border-slate-100 pb-6">
        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center font-bold text-2xl text-white shadow-md uppercase">
            {{ substr(Auth::user()->name, 0, 2) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ Auth::user()->name }}</h2>
            
            <p class="text-sm font-bold mt-1 uppercase tracking-widest text-[11px] px-2.5 py-0.5 rounded-md inline-block
                {{ Auth::user()->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                Hak Akses: {{ Auth::user()->role === 'admin' ? 'Admin / Sarpras' : 'Mahasiswa' }}
            </p>
        </div>
    </div>

    <div class="space-y-4 text-sm">
        <div class="grid grid-cols-3 py-2 border-b border-slate-50">
            <span class="text-slate-400 font-medium">Email Terdaftar</span>
            <span class="col-span-2 font-semibold text-slate-800">{{ Auth::user()->email }}</span>
        </div>
        
        <div class="grid grid-cols-3 py-2 border-b border-slate-50">
            <span class="text-slate-400 font-medium">Status Otentikasi</span>
            <span class="col-span-2 font-semibold text-green-600">✓ Aktif Sesi Web & Mobile</span>
        </div>

        <div class="grid grid-cols-3 py-4 bg-slate-50 p-4 rounded-xl items-start mt-4">
            <span class="text-slate-400 font-bold text-xs uppercase tracking-wider mt-1">Deskripsi Tugas</span>
            <div class="col-span-2 space-y-1">
                @if(Auth::user()->role === 'admin')
                    <span class="text-slate-800 font-bold text-sm block">🛡️ Otoritas Validasi Kampus</span>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Anda masuk sebagai administrator sistem. Anda memiliki wewenang penuh untuk meninjau laporan keluhan, mengubah status penanganan (Pending, Proses, Selesai), serta menghapus aduan dan komentar sampah yang dinilai tidak pantas.
                    </p>
                @else
                    <span class="text-slate-800 font-bold text-sm block">🎓 Hak Suara Mahasiswa</span>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Anda masuk sebagai pengguna reguler/mahasiswa. Anda memiliki hak untuk mengirimkan aspirasi atau keluhan sarana prasarana kampus, memantau tindak lanjut keluhan Anda, serta memberikan tanggapan balasan pada komentar admin.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection