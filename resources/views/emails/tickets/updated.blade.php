<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang Di Haven Kos</title>
</head>
<body>
    <h2>Halo {{ $ticket->user->name }},</h2>
    <p>Tiket Anda telah diperbarui:</p>
    <ul>
        <li><b>Judul:</b> {{ $ticket->title }}</li>
        <li><b>Kategori:</b> {{ $ticket->category ?? '-' }}</li>
        <li><b>Status:</b> {{ ucfirst($ticket->status) }}</li>
    </ul>
</body>
</html>
