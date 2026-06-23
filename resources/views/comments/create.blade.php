<!DOCTYPE html>
<html>
<head>
    <title>Tambah Comment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-900 to-blue-900 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-xl bg-white/10 backdrop-blur-xl border border-white/20 text-white p-6 rounded-2xl shadow-xl">

    <h1 class="text-2xl font-bold mb-6">Tambah Comment</h1>

    <form action="/comments" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="text-sm text-gray-200">Pilih Complaint</label>
            <select name="complaint_id" required
                class="w-full mt-1 p-2 rounded bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" class="text-slate-900">Pilih Complaint</option>
                @foreach($complaints as $c)
                    <option value="{{ $c->id }}" class="text-slate-900">{{ $c->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm text-gray-200">Comment</label>
            <textarea name="message" required
                class="w-full mt-1 p-2 rounded bg-white/10 border border-white/20 text-white h-28 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 transition py-2 rounded-lg font-semibold text-white">
            Kirim Comment
        </button>

        <a href="/comments"
           class="block text-center text-sm text-gray-300 hover:text-white mt-2">
            Kembali
        </a>
    </form>

</div>

</body>
</html>