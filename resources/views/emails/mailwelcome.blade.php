<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang Di Haven Kos</title>
</head>
<body>
    <h1>Selamat Datang, {{ $nama }}!</h1>
    <p>Terima kasih telah mendaftar di platform kami.</p>

    <p><strong>Nama:</strong> {{ $nama }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Status:</strong> {{ $user->status }}</p>

    <a href="{{ url('/dashboard') }}" style="display:inline-block;padding:10px 20px;background:#28a745;color:white;text-decoration:none;border-radius:4px;">
        Masuk ke Dashboard
    </a>
</body>
</html>
