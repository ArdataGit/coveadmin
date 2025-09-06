<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang Di Haven Kos</title>
</head>
<body>
    <h2>Halo {{ $ticket->user->name }},</h2>
    <p>Admin telah memberikan respon untuk tiket Anda:</p>
    <p><b>Respon Admin:</b> {{ $ticket->admin_response }}</p>
    <p><b>Status Tiket:</b> {{ ucfirst($ticket->status) }}</p>
</body>
</html>
