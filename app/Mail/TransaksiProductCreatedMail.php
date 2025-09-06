<?php

namespace App\Mail;

use App\Models\TransaksiProduk;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransaksiProductCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    public function __construct(TransaksiProduk $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function build()
    {
        return $this->subject('Transaksi Produk Baru #' . $this->transaksi->no_order)
                    ->view('emails.transactionproductcreated')
                    ->with([
                        'trx' => $this->transaksi,
                    ]);
    }
}
