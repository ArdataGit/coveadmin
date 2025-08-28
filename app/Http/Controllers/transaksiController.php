<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosDetail;
use App\Models\PaketHarga;
use App\Models\Transaksi;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;

class transaksiController extends Controller
{
    /**
     * Menampilkan semua transaksi dengan search dan filter
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'kos', 'kamar', 'pembayaran']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('no_order', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('kos', function ($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $transaksis = $query->latest()->get();
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
    public function storeweb(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'kos_id'            => 'required|exists:kos,id',
            'kamar_id'          => 'required|exists:kos_detail,id',
            'paket_id'          => 'required|exists:paket_harga,id',
            'tanggal'           => 'required|date',
            'harga'             => 'required|integer|min:0',
            'quantity'          => 'nullable|integer|min:1',
            'start_order_date'  => 'nullable|date',
            'end_order_date'    => 'nullable|date|after_or_equal:start_order_date',
        ]);

        $transaksi = Transaksi::create([
            'user_id'            => $request->user_id,
            'no_order'           => 'INV/' . strtoupper(Str::random(8)),
            'tanggal'            => $request->tanggal,
            'start_order_date'   => $request->start_order_date,
            'end_order_date'     => $request->end_order_date,
            'kos_id'             => $request->kos_id,
            'kamar_id'           => $request->kamar_id,
            'paket_id'           => $request->paket_id,
            'harga'              => $request->harga,
            'quantity'           => $request->quantity ?? 1,
            'status'             => 'pending',
        ]);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dibuat');
    }
  
  public function store(Request $request)
{
    $request->validate([
        'user_id'           => 'required|exists:users,id',
        'kos_id'            => 'required|exists:kos,id',
        'kamar_id'          => 'required|exists:kos_detail,id',
        'paket_id'          => 'required|exists:paket_harga,id',
        'tanggal'           => 'required|date',
        'harga'             => 'required|integer|min:0',
        'quantity'          => 'nullable|integer|min:1',
        'start_order_date'  => 'nullable|date',
        'end_order_date'    => 'nullable|date|after_or_equal:start_order_date',
    ]);

    $transaksi = Transaksi::create([
        'user_id'            => $request->user_id,
        'no_order'           => 'INV/' . strtoupper(Str::random(8)),
        'tanggal'            => $request->tanggal,
        'start_order_date'   => $request->start_order_date,
        'end_order_date'     => $request->end_order_date,
        'kos_id'             => $request->kos_id,
        'kamar_id'           => $request->kamar_id,
        'paket_id'           => $request->paket_id,
        'harga'              => $request->harga,
        'quantity'           => $request->quantity ?? 1,
        'status'             => 'pending',
    ]);

    return response()->json([
        'success'   => true,
        'message'   => 'Transaksi berhasil dibuat',
        'data'      => $transaksi
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

            $transaksi = Transaksi::with('pembayaran')->findOrFail($id);
            $transaksi->status = $request->status;
            $transaksi->save();

            // Jika sudah dibayar, kurangi stok kamar sesuai quantity
            if ($request->status === 'paid') {
                $pembayaran = $transaksi->pembayaran()->whereIn('tipe_bayar', ['dp', 'full'])->first();
                if ($pembayaran) {
                    $kosDetail = KosDetail::find($transaksi->kamar_id);

                    if ($kosDetail && $kosDetail->stok >= $transaksi->quantity) {
                        $kosDetail->stok -= $transaksi->quantity;
                        $kosDetail->save();
                    } else {
                        return redirect()->route('transaksi.index')
                            ->with('error', 'Stok kamar tidak cukup untuk transaksi ini');
                    }
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
     * Tambah pembayaran baru untuk transaksi
     */
    public function pembayaran(Request $request, $id)
    {
        try {
            $request->validate([
                'nominal'     => 'required|integer|min:0',
                'keterangan'  => 'nullable|string|max:255',
                'tanggal'     => 'required|date',
                'tipe_bayar'  => 'required|in:dp,full',
                'jenis_bayar' => 'required|in:biaya_kos,tagihan,denda',
            ]);

            $transaksi = Transaksi::findOrFail($id);

            // Create new pembayaran record
            $pembayaran = Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'tanggal'      => $request->tanggal,
                'jenis_bayar'  => $request->jenis_bayar,
                'tipe_bayar'   => $request->tipe_bayar,
                'keterangan'   => $request->keterangan,
                'nominal'      => $request->nominal,
                'status'       => 'belum_lunas',
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
            $transaksi->delete(); // Pembayaran records will be deleted via ON DELETE CASCADE

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function invoice($id)
    {
        $trx = Transaksi::with(['user', 'kos', 'kamar', 'pembayaran'])->findOrFail($id);

        // Define $total as $trx->harga (or adjust based on your logic)
        $total = $trx->harga;

        // Clean filename for the PDF
        $safeFileName = 'Invoice-' . Str::slug($trx->no_order, '-');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.kos_invoice', compact('trx', 'total'))
                 ->setPaper('A4', 'portrait');

        return $pdf->stream($safeFileName . '.pdf');
    }

    public function getByUser(Request $request, $userId)
    {

        // dd($userId);
        try {
            // Validasi user_id
            if (!is_numeric($userId) || $userId <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user ID'
                ], 400);
            }

            // Cek apakah user ada
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Query transaksi berdasarkan user_id dengan relasi
            $query = Transaksi::with(['pembayaran', 'kos', 'kamar'])
                ->where('user_id', $userId);

            // Search (opsional, jika ada parameter search)
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_order', 'like', "%{$search}%")
                    ->orWhereHas('kos', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kamar', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
                });
            }

            // Filter status (opsional)
            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }

            // Urutkan dari yang terbaru
            $transaksis = $query->latest()->get();

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Transaksi retrieved successfully',
                'data' => $transaksis
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}