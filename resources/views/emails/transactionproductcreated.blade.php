<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaksi Produk Baru</title>
</head>
<body>
    <h2>Halo {{ $trx->user->nama }},</h2>
    <p>Transaksi produk Anda berhasil dibuat dengan detail berikut:</p>

    <ul>
        <li><strong>No Order:</strong> {{ $trx->no_order }}</li>
        <li><strong>Produk:</strong> {{ $trx->produk->judul_produk }}</li>
        <li><strong>Jumlah:</strong> {{ $trx->jumlah }}</li>
        <li><strong>Harga Satuan:</strong> Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}</li>
        <li><strong>Subtotal:</strong> Rp {{ number_format($trx->subtotal, 0, ',', '.') }}</li>
        <li><strong>Status:</strong> {{ ucfirst($trx->status) }}</li>
        <li><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d-m-Y') }}</li>
    </ul>

    <!-- <p>
        <a href="{{ url('/dashboard/transaksi-produk/' . $trx->id_transaksi) }}" 
           style="display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:4px;">
           Lihat Transaksi
        </a>
    </p> -->

    <p>Terima kasih,<br>Haven Kos</p>
</body>
</html>
