<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaksi Baru</title>
</head>
<body>
    <h2>Halo {{ $trx->user->nama }},</h2>
    <p>Transaksi Anda berhasil dibuat dengan detail berikut:</p>

    <ul>
        <li><strong>No Order:</strong> {{ $trx->no_order }}</li>
        <li><strong>Kos:</strong> {{ $trx->kos->nama }}</li>
        <li><strong>Kamar:</strong> {{ $trx->kamar->nama }}</li>
        <li><strong>Harga:</strong> Rp {{ number_format($trx->harga, 0, ',', '.') }}</li>
        <li><strong>Status:</strong> {{ ucfirst($trx->status) }}</li>
    </ul>

    <!-- <p>
        <a href="{{ url('/dashboard/transaksi/' . $trx->id) }}" 
           style="display:inline-block;padding:10px 20px;background:#28a745;color:white;text-decoration:none;border-radius:4px;">
           Lihat Transaksi
        </a>
    </p> -->

    <p>Terima kasih,<br>Haven Kos</p>
</body>
</html>
