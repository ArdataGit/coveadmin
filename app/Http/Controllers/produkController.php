<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\ProdukGambar;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // List produk
    public function index()
    {
        $produk = Produk::with(['kategori', 'gambar'])->latest()->paginate(10);
        $kategori = Kategori::get();
        return view('/admin/produk', compact('produk', 'kategori'));
    }

    // Menampilkan galeri gambar berdasarkan id produk
    public function indexGambar($id)
    {
        $produk = Produk::with('gambar')->findOrFail($id);
        $gambar = ProdukGambar::where('id_produk', $id)->get();
        return view('/admin/galeri-produk', compact('produk', 'gambar'));
    }

    // Form tambah produk
    public function create()
    {
        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'judul_produk' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric',
            'id_kategori'  => 'required|exists:kategori,id_kategori',
        ]);

        // Simpan data produk
        $produk = Produk::create([
            'judul_produk' => $request->judul_produk,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
            'id_kategori'  => $request->id_kategori,
        ]);

        // Simpan gambar (panggil function terpisah)
        $this->storeGambar($request, $produk->id_produk);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // Simpan gambar produk (fungsi terpisah, ke public/assets/produk)
    private function storeGambar(Request $request, $id_produk)
    {
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/produk'), $filename);

                ProdukGambar::create([
                    'id_produk'  => $id_produk,
                    'url_gambar' => 'assets/produk/' . $filename,
                ]);
            }
        }
    }
    
    // Simpan gambar baru untuk produk tertentu
    public function storeGambarOnly(Request $request, $id_produk)
    {
        $request->validate([
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Pastikan produk ada
        Produk::findOrFail($id_produk);

        // Panggil fungsi storeGambar untuk menyimpan gambar
        $this->storeGambar($request, $id_produk);

        return redirect()->route('produk.gambar', $id_produk)->with('success', 'Gambar berhasil diunggah');
    }

    // Tampilkan detail produk
    public function show($id)
    {
        $produk = Produk::with(['kategori', 'gambar'])->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    // Form edit produk
    public function edit($id)
    {
        $produk = Produk::with('gambar')->findOrFail($id);
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'judul_produk' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric',
            'id_kategori'  => 'required|exists:kategori,id_kategori',
        ]);

        // Update data produk
        $produk->update([
            'judul_produk' => $request->judul_produk,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
            'id_kategori'  => $request->id_kategori,
        ]);

        // Simpan gambar baru (jika ada)
        $this->storeGambar($request, $produk->id_produk);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    // Hapus satu gambar produk (function terpisah)
    public function destroyGambar($id_gambar)
    {
        $gambar = ProdukGambar::findOrFail($id_gambar);

        // Hapus file fisik
        $filePath = public_path($gambar->url_gambar);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus dari database
        $gambar->delete();

        return back()->with('success', 'Gambar berhasil dihapus');
    }

    // Hapus produk beserta semua gambar
    public function destroy($id)
    {
        $produk = Produk::with('gambar')->findOrFail($id);

        // Hapus semua gambar terkait
        foreach ($produk->gambar as $gambar) {
            $filePath = public_path($gambar->url_gambar);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $gambar->delete();
        }

        // Hapus produk
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
  
    public function getById($id)
      {

          try {
              $produk = Produk::with(['kategori', 'gambar'])->findOrFail($id);


              return response()->json([
                  'success' => true,
                  'data' => [
                      'id_produk' => $produk->id_produk,
                      'judul_produk' => $produk->judul_produk,
                      'deskripsi' => $produk->deskripsi,
                      'harga' => $produk->harga,
                      'id_kategori' => $produk->id_kategori,
                      'kategori' => $produk->kategori ? [
                          'id_kategori' => $produk->kategori->id_kategori,
                          'nama_kategori' => $produk->kategori->nama_kategori
                      ] : null,
                      'gambar' => $produk->gambar->map(function ($gambar) {
                          return [
                              'id_gambar' => $gambar->id_gambar,
                              'url_gambar' => asset($gambar->url_gambar)
                          ];
                      })->toArray(),
                      'created_at' => $produk->created_at->format('d M Y H:i'),
                  ]
              ]);
          } catch (ModelNotFoundException $e) {
              \Log::error('Produk tidak ditemukan', ['id_produk' => $id, 'error' => $e->getMessage()]);

              return response()->json([
                  'success' => false,
                  'message' => 'Produk tidak ditemukan'
              ], 404);
          }
      }
  
  	public function getAllData(Request $request)
    {
        $search = $request->query('search');
        $id_kategori = $request->query('id_kategori');
        $perPage = $request->query('per_page', 10);

        \Log::info('=== getAllData Request Params ===', [
            'search' => $search,
            'id_kategori' => $id_kategori,
            'per_page' => $perPage
        ]);

        $query = Produk::with(['kategori', 'gambar'])->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($q2) use ($search) {
                      $q2->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }

        if ($id_kategori) {
            $query->where('id_kategori', $id_kategori);
        }

        $result = $query->paginate($perPage);

        \Log::info('Jumlah data setelah filter', ['count' => $result->total()]);

        return response()->json([
            'success' => true,
            'data' => $result->items(),
            'meta' => [
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem(),
            ]
        ]);
    }
}