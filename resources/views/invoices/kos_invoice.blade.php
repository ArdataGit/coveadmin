<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $trx->no_order }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; }
        .header h1 { margin: 0; font-size: 20px; color: #0070c0; }
        .header h3 { margin: 0; font-weight: normal; }
        .info-table td { padding: 4px 8px; vertical-align: top; }
        .amount-box {
            border: 1px solid #000;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 16px;
            background-color: #f5f5f5;
            display: inline-block;
        }
        .payment-box {
            border: 1px solid #000;
            padding: 8px;
            margin-top: 15px;
            font-size: 11px;
        }
        .signature { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kos Rahmani</h1>
        <h3>Kos-kosan</h3>
        <p>Jl. Kebagusan 1 Rt. 001 Rw. 01 No. 16 Kelurahan Kebagusan Kecamatan Pasar Minggu Jakarta Selatan 12520<br>
        Tlp. 021-7807028 , Mobile : 0818 0860 9 222</p>
    </div>

    <table class="info-table" width="100%">
        <tr>
            <td width="25%">Nomor</td>
            <td>: {{ $trx->no_order }}</td>
        </tr>
        <tr>
            <td>Sudah Terima Dari</td>
            <td>: {{ $trx->user->nama }}</td>
        </tr>
        <tr>
            <td>Banyaknya Uang</td>
            <td>: <strong style="background-color: #ddd; padding: 2px 4px;">{{ number_format($total, 0, ',', '.') }}  RUPIAH</strong></td>
        </tr>
        <tr>
            <td>Untuk Pembayaran</td>
            <td>: Sewa Kamar {{ $trx->kamar->nama }} Periode {{ $trx->periode }} Bln s/d {{ date('d F Y', strtotime($trx->end_order_date)) }}</td>
        </tr>
    </table>

    <p><strong>Jumlah Rp.</strong> <span class="amount-box">{{ number_format($trx->harga, 0, ',', '.') }},-</span></p>

    <div class="payment-box">
        Untuk Melakukan Pembayaran Dapat Melalui Rekening:<br>
        1. BCA No. 5540211598 a.n Rahmadoni<br>
        2. Mandiri No. 127-0-9452710 a.n Rahmadoni<br>
        Mohon konfirmasi ke no 0818-0860-9222 a.n Rahmadoni apabila sudah melakukan pembayaran.
    </div>

    <div class="signature">
        Jakarta, {{ date('d F Y', strtotime($trx->tanggal)) }}<br>
        Rahmani’s House<br><br><br>
        <strong>RAHMADONI</strong><br>
        Pemilik
    </div>
</body>
</html>
