<!DOCTYPE html>
<html>
<head>
    <title>Comments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen p-8">

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Komentar</h1>

        <a href="/comments/create"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Tambah Comment
        </a>
    </div>

    <div class="space-y-4">

        @foreach($comments as $comment)

        <div class="bg-white shadow-md rounded-xl p-5 hover:shadow-xl transition">

            <p class="text-sm font-semibold text-blue-600">
                Complaint: {{ $comment->complaint ? $comment->complaint->title : 'ID ' . $comment->complaint_id }}
            </p>

            <p class="mt-2 text-gray-800 text-lg font-medium">
                {{ $comment->message }}
            </p>

            <div class="mt-4 flex gap-2">

                <a href="/comments/{{ $comment->id }}/edit"
                   class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">
                    Edit
                </a>

                <form action="/comments/{{ $comment->id }}" method="POST"
                      onsubmit="return confirm('Hapus komentar ini?')">
                    @csrf
                    @method('DELETE')

                    <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        Hapus
                    </button>
                </form>

            </div>

        </div>

        @endforeach

    </div>

</div>

</body>
</html>