@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
    <div class="mb-6">
        <div class="text-xs font-medium text-slate-400 flex items-center gap-2">
            <a href="/dashboard" class="hover:text-blue-600">Dashboard</a> <span>&rsaquo;</span> 
            <a href="/complaints" class="hover:text-blue-600">Daftar Aduan</a> <span>&rsaquo;</span> 
            <span class="text-slate-600 font-semibold">Edit Aduan</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Edit Aduan</h1>
    </div>

    <form action="/complaints/{{ $complaint->id }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Aduan</label>
            <input type="text" name="title" required value="{{ old('title', $complaint->title) }}"
                   class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</label>
            <select name="category_id" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($categories ?? \App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}" @selected($category->id == $complaint->category_id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi Kejadian</label>
            <input type="text" name="location" value="{{ old('location', $complaint->location) }}"
                   class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</label>
            <textarea name="description" rows="4" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $complaint->description) }}</textarea>
        </div>

        <div>
            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Ganti / Perbarui Lampiran Bukti Foto</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto bukti lama.</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md shadow-blue-600/10">
            Update Aduan
        </button>
    </form>
</div>
@endsection