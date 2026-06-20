<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <h1 class="text-3xl font-bold mb-6">
        Dashboard
    </h1>

    <div class="grid grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded shadow">
            <h2>Total Complaint</h2>
            <p class="text-4xl">{{ $complaints }}</p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2>Total Comment</h2>
            <p class="text-4xl">{{ $comments }}</p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2>Total Attachment</h2>
            <p class="text-4xl">{{ $attachments }}</p>
        </div>

    </div>

</body>
</html>