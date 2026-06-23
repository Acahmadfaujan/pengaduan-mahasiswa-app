<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIKELUH</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl p-8">
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-blue-600 text-white font-bold text-xl rounded-2xl flex items-center justify-center mx-auto shadow-md shadow-blue-600/20">S</div>
        <h1 class="text-xl font-bold text-slate-900 mt-3">Selamat Datang Kembali</h1>
        <p class="text-xs text-slate-400 font-medium mt-1">Masuk ke sistem pengaduan SIKELUH</p>
    </div>

    <!-- TAMPILAN ERROR YANG SUDAH FIX -->
    @if($errors->any())
        <div class="bg-red-50 text-red-600 border border-red-100 p-3 rounded-xl text-xs font-semibold mb-4">
            {{ $errors->first('email') }} <!-- FIX: Menggunakan first() bukan firsty() -->
        </div>
    @endif

    <form action="/login" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 text-xs font-bold text-slate-500 uppercase">Email</label>
            <input type="email" name="email" required placeholder="nama@gmail.com" value="{{ old('email') }}"
                   class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1 text-xs font-bold text-slate-500 uppercase">Password</label>
            <input type="password" name="password" required placeholder="••••••••"
                   class="w-full p-3 text-sm rounded-xl bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md shadow-blue-600/10">
            Masuk Sekarang
        </button>
    </form>

    <p class="text-center text-xs text-slate-400 mt-6 font-medium">
        Belum punya akun? <a href="/register" class="text-blue-600 font-bold hover:underline">Daftar Akun</a>
    </p>
</div>

</body>
</html>