<?php

namespace App\Mail;

use App\Models\Transaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransaksiCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Transaksi Baru #' . $this->transaksi->no_order)
                    ->view('emails.transactioncreated')
                    ->with([
                        'trx' => $this->transaksi,
                    ]);
    }
}
