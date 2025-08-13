<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class pembayaranController extends Controller
{
    /**
     * Menampilkan semua pembayaran untuk transaksi tertentu.
     */
    public function index($transaksi_id)
    {
        $transaksi = Transaksi::findOrFail($transaksi_id);
        $pembayarans = Pembayaran::where('transaksi_id', $transaksi_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.pembayaran', compact('transaksi', 'pembayarans'));
    }

    /**
     * Membuat pembayaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'tanggal'      => 'required|date',
            'jenis_bayar'  => 'required|in:biaya_kos,tagihan,denda',
            'tipe_bayar'   => 'required|in:dp,full',
            'keterangan'   => 'nullable|string|max:255',
            'nominal'      => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Buat pembayaran baru
            $pembayaran = Pembayaran::create([
                'transaksi_id' => $request->transaksi_id,
                'tanggal'      => $request->tanggal,
                'jenis_bayar'  => $request->jenis_bayar,
                'tipe_bayar'   => $request->tipe_bayar,
                'keterangan'   => $request->keterangan,
                'nominal'      => $request->nominal,
            ]);

            // Generate kode pembayaran format: PEM/000001
            $pembayaran->kode_pembayaran = 'PEM/' . str_pad($pembayaran->pembayaran_id, 6, '0', STR_PAD_LEFT);
            $pembayaran->save();

            // Cek apakah ini pembayaran pertama untuk transaksi ini
            $jumlahPembayaran = Pembayaran::where('transaksi_id', $request->transaksi_id)->count();
            if ($jumlahPembayaran === 1) {
                Transaksi::where('id', $request->transaksi_id)->update([
                    'status' => 'booked'
                ]);
            }

            DB::commit();

            return redirect()->route('pembayaran.index', $request->transaksi_id)
                ->with('success', 'Pembayaran berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pembayaran.index', $request->transaksi_id)
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Update pembayaran.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'jenis_bayar'  => 'required|in:biaya_kos,tagihan,denda',
            'tipe_bayar'   => 'required|in:dp,full',
            'keterangan'   => 'nullable|string|max:255',
            'nominal'      => 'required|integer|min:0',
        ]);

        try {
            $pembayaran = Pembayaran::findOrFail($id);
            $pembayaran->update([
                'tanggal'      => $request->tanggal,
                'jenis_bayar'  => $request->jenis_bayar,
                'tipe_bayar'   => $request->tipe_bayar,
                'keterangan'   => $request->keterangan,
                'nominal'      => $request->nominal,
            ]);

            return redirect()->route('pembayaran.index', $pembayaran->transaksi_id)
                ->with('success', 'Pembayaran berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('pembayaran.index', $pembayaran->transaksi_id ?? $request->transaksi_id)
                ->with('error', 'Gagal memperbarui pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pembayaran.
     */
    public function destroy($id)
    {
        try {
            $pembayaran = Pembayaran::findOrFail($id);
            $transaksi_id = $pembayaran->transaksi_id;
            $pembayaran->delete();

            return redirect()->route('pembayaran.index', $transaksi_id)
                ->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('pembayaran.index', $transaksi_id ?? null)
                ->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
