<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIKELUH</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl p-8">
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-blue-600 text-white font-bold text-xl rounded-2xl flex items-center justify-center mx-auto shadow-md shadow-blue-600/20">S</div>
        <h1 class="text-xl font-bold text-slate-900 mt-3">Buat Akun Baru</h1>
        <p class="text-xs text-slate-400 font-medium mt-1">Pilih tipe akun Anda untuk memulai pelaporan</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-600 border border-red-100 p-3 rounded-xl text-xs font-semibold mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/register" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 text-xs font-bold text-slate-500 uppercase">Nama Lengkap</label>
            <input type="text" name="name" required placeholder="Nama Lengkap Anda" value="{{ old('name') }}"
                   class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1 text-xs font-bold text-slate-500 uppercase">Email</label>
            <input type="email" name="email" required placeholder="nama@mahasiswa.ac.id" value="{{ old('email') }}"
                   class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1 text-xs font-bold text-slate-500 uppercase">Daftar Sebagai (Role)</label>
            <select name="role" required class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="user" @selected(old('role') == 'user')>User / Mahasiswa</option>
                <option value="admin" @selected(old('role') == 'admin')>Admin / Petugas</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block mb-1 text-xs font-bold text-slate-500 uppercase">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block mb-1 text-xs font-bold text-slate-500 uppercase">Konfirmasi</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                       class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md shadow-blue-600/10">
            Daftar Sekarang
        </button>
    </form>

    <p class="text-center text-xs text-slate-400 mt-6 font-medium">
        Sudah punya akun? <a href="/login" class="text-blue-600 font-bold hover:underline">Masuk Akun</a>
    </p>
</div>

</body>
</html>