<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosDetail;
use App\Models\PaketHarga;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    /**
     * Menampilkan semua transaksi
     */
    public function index()
    {
        $transaksis = Transaksi::with(['user', 'kos', 'kamar'])->latest()->get();
        $users = User::all();
        $kos = Kos::all();
        $kamars = KosDetail::all();
        $pakets = PaketHarga::all();

        return view('admin.transaksi', compact(
            'transaksis',
            'users',
            'kos',
            'kamars',
            'pakets'
        ));
    }

    /**
     * Membuat transaksi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'tanggal'           => 'required|date',
            'tipe_bayar'        => 'required|in:dp,full',
            'jenis_bayar'       => 'required|in:biaya_kos,tagihan,denda',
            'harga'             => 'required|integer|min:0',
            'nominal'           => 'required|integer|min:0',
            'quantity'          => 'nullable|integer|min:1',
            'start_order_date'  => 'nullable|date',
            'end_order_date'    => 'nullable|date|after_or_equal:start_order_date',
        ]);

        $transaksi = Transaksi::create([
            'user_id'            => $request->user_id,
            'no_order'           => 'ORD-' . strtoupper(Str::random(8)),
            'tanggal'            => $request->tanggal,
            'start_order_date'   => $request->start_order_date,
            'end_order_date'     => $request->end_order_date,
            'kos_id'             => $request->kos_id,
            'kamar_id'           => $request->kamar_id,
            'paket_id'           => $request->paket_id,
            'harga'              => $request->harga,
            'nominal'            => $request->nominal,
            'quantity'           => $request->quantity ?? 1,
            'keterangan'         => $request->keterangan,
            'tipe_bayar'         => $request->tipe_bayar,
            'jenis_bayar'        => $request->jenis_bayar,
            'methode_pembayaran' => $request->methode_pembayaran,
            'status'             => 'unpaid',
        ]);

        return response()->json([
            'message' => 'Transaksi berhasil dibuat',
            'data'    => $transaksi
        ], 201);
    }

    /**
     * Update status transaksi
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:paid,unpaid,cancel',
            ]);

            $transaksi = Transaksi::findOrFail($id);
            $transaksi->status = $request->status;
            $transaksi->save();

            // Jika sudah dibayar, kurangi stok kamar sesuai quantity
            if ($request->status === 'paid' && in_array($transaksi->tipe_bayar, ['dp', 'full'])) {
                $kosDetail = KosDetail::find($transaksi->kamar_id);

                if ($kosDetail && $kosDetail->stok >= $transaksi->quantity) {
                    $kosDetail->stok -= $transaksi->quantity;
                    $kosDetail->save();
                } else {
                    return redirect()->route('transaksi.index')
                        ->with('error', 'Stok kamar tidak cukup untuk transaksi ini');
                }
            }

            return redirect()->route('transaksi.index')
                ->with('success', 'Status transaksi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal memperbarui status transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Tambah pembayaran baru
     */
    public function pembayaran(Request $request, $id)
    {
        try {
            $request->validate([
                'nominal'     => 'required|integer|min:0',
                'keterangan'  => 'nullable|string|max:255',
            ]);

            // Ambil transaksi lama
            $oldTransaksi = Transaksi::findOrFail($id);

            // Insert transaksi baru dengan data yang sama kecuali nominal & keterangan
            $newTransaksi = Transaksi::create([
                'user_id'            => $oldTransaksi->user_id,
                'no_order'           => $oldTransaksi->no_order, // tetap sama
                'tanggal'            => now()->format('Y-m-d'),
                'start_order_date'   => $oldTransaksi->start_order_date,
                'end_order_date'     => $oldTransaksi->end_order_date,
                'kos_id'             => $oldTransaksi->kos_id,
                'kamar_id'           => $oldTransaksi->kamar_id,
                'paket_id'           => $oldTransaksi->paket_id,
                'harga'              => $oldTransaksi->harga,
                'nominal'            => $request->nominal, // baru
                'quantity'           => $oldTransaksi->quantity,
                'keterangan'         => $request->keterangan, // baru
                'tipe_bayar'         => $oldTransaksi->tipe_bayar,
                'jenis_bayar'        => $oldTransaksi->jenis_bayar,
                'methode_pembayaran' => $oldTransaksi->methode_pembayaran,
                'status'             => 'paid', // default paid untuk pembayaran baru
            ]);

            return redirect()->route('transaksi.index')
                ->with('success', 'Pembayaran baru berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal membuat pembayaran baru: ' . $e->getMessage());
        }
    }

    /**
     * Hapus transaksi
     */
    public function destroy($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}