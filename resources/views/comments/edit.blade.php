<!DOCTYPE html>
<html>
<head>
    <title>Edit Comment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-xl bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Comment</h1>

    <form action="/comments/{{ $comment->id }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-semibold text-gray-600">Complaint</label>
            <select name="complaint_id" required
                class="w-full mt-1 p-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($complaints as $c)
                    <option value="{{ $c->id }}" {{ $c->id == $comment->complaint_id ? 'selected' : '' }}>
                        {{ $c->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold text-gray-600">Comment</label>
            <textarea name="message" required
                class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 h-28 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $comment->message }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="/comments"
               class="flex-1 text-center bg-gray-200 hover:bg-gray-300 transition text-gray-700 py-2 rounded-lg font-semibold">
                Batal
            </a>
            
            <button type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-700 transition py-2 rounded-lg font-semibold text-white">
                Update
            </button>
        </div>
    </form>

</div>

</body>
</html>