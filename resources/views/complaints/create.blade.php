@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
    <div class="mb-6">
        <div class="text-xs font-medium text-slate-400 flex items-center gap-2">
            <a href="/dashboard" class="hover:text-blue-600">Dashboard</a> <span>&rsaquo;</span> <span class="text-slate-600 font-semibold">Buat Aduan</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Buat Aduan</h1>
    </div>

    <form action="/complaints" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Aduan</label>
            <input type="text" name="title" required placeholder="Contoh: Wifi Gedung A Lemot"
                   class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</label>
            <select name="category_id" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Pilih kategori</option>
                @foreach($categories ?? \App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi Kejadian</label>
            <input type="text" name="location" placeholder="Contoh: Gedung A Lantai 3"
                   class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</label>
            <textarea name="description" rows="4" required placeholder="Jelaskan secara detail aduan Anda..."
                      class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Lampiran Bukti Foto</label>
            <input type="file" name="image" required accept="image/*"
                   class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-[11px] text-slate-400 mt-1">Format: JPG, JPEG, PNG, WEBP (Maksimal 3MB)</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md shadow-blue-600/10">
            Kirim Aduan
        </button>
    </form>
</div>
@endsection