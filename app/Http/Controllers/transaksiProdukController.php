<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\TransaksiProduk;
use App\Models\User;
use Illuminate\Http\Request;

class transaksiProdukController extends Controller
{
    // Tampilkan semua transaksi
    public function index()
    {
        $transaksi = TransaksiProduk::with(['user', 'produk'])->paginate(10);
        $users = User::get();
        $produk = Produk::get();
        
        return view('admin.transaksi-produk', compact('transaksi','users','produk'));
    }

    // Form tambah transaksi
    public function create()
    {
        return view('transaksi.create');
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_produk' => 'required|exists:produk,id_produk',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:belum_lunas,lunas,dibatalkan',
        ]);

        $last = TransaksiProduk::latest('id_transaksi')->first();
        $nextId = $last ? $last->id_transaksi + 1 : 1;
        $no_order = 'PRDK-' . now()->format('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $transaksi = TransaksiProduk::create([
            'no_order' => $no_order,
            'id_user' => $request->id_user,
            'id_produk' => $request->id_produk,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'subtotal' => $request->subtotal,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'data' => $transaksi
        ], 201);
    }



    // Detail transaksi
    public function show($id)
    {
        $transaksi = TransaksiProduk::with(['user', 'produk'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    // Form edit transaksi
    public function edit($id)
    {
        $transaksi = TransaksiProduk::findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }

    // Update transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'status' => 'required|in:belum_lunas,lunas,dibatalkan',
        ]);

        $transaksi = TransaksiProduk::findOrFail($id);
        $transaksi->update($request->all());

        return redirect()->route('transaksi-produk.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    // Hapus transaksi
    public function destroy($id)
    {
        $transaksi = TransaksiProduk::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi-produk.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function getByUserProduk(Request $request, $userId)
    {
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

            // Query transaksi produk berdasarkan id_user dengan relasi
            $query = TransaksiProduk::with(['user', 'produk'])
                ->where('id_user', $userId);

            // Search (opsional, jika ada parameter search)
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_order', 'like', "%{$search}%")
                    ->orWhereHas('produk', function ($q) use ($search) {
                        $q->where('judul_produk', 'like', "%{$search}%")
                            ->orWhere('deskripsi', 'like', "%{$search}%");
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
                'message' => 'Transaksi produk retrieved successfully',
                'data' => $transaksis
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve transaksi produk: ' . $e->getMessage()
            ], 500);
        }
    }

}
