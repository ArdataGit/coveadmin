<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosDetail;
use App\Models\Lokasi;
use App\Models\TipeKos;
use App\Models\Lantai;
use App\Models\Fasilitas;
use App\Models\GalleryKos;
use App\Models\PaketHarga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class kosController extends Controller
{
    /**
     * Display the master kos index view.
     */
    public function index()
    {
        $kos = Kos::all();
        $lokasi = Lokasi::all();
        return view('admin.master-kos', compact('kos', 'lokasi'));
    }

    /**
     * Fetch master kos data for the table via AJAX.
     */
    public function data(Request $request)
    {
        $search = $request->query('search');

        $query = Kos::with('daerah')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('alamat_kota', 'like', "%$search%")
                  ->orWhere('keterangan', 'like', "%$search%");
            });
        }

        $kos = $query->get();

        return response()->json($kos);
    }

    /**
     * Store a new kos record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat_kota' => 'required|string|max:255',
            'daerah_id' => 'required|integer|exists:lokasi,id',
            'keterangan' => 'nullable|string',
            'link_maps' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $kos = Kos::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kos added successfully.',
            'data' => $kos,
        ]);
    }

    /**
     * Update an existing kos record.
     */
    public function update(Request $request, $id)
    {
        $kos = Kos::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat_kota' => 'required|string|max:255',
            'daerah_id' => 'required|integer|exists:lokasi,id',
            'keterangan' => 'nullable|string',
            'link_maps' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $kos->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kos updated successfully.',
            'data' => $kos,
        ]);
    }

    /**
     * Delete a kos record.
     */
    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);
        $kos->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kos deleted successfully.',
        ]);
    }

    /**
     * Display the kos detail page.
     */
    public function detail($kos_id)
    {
        $kos = Kos::findOrFail($kos_id);
        $kosDetails = KosDetail::where('kos_id', $kos_id)->get();
        $tipeKos = TipeKos::all();
        $lantai = Lantai::all();
        $fasilitas = Fasilitas::all();
        return view('admin.kos-detail', compact('kos', 'kosDetails', 'tipeKos', 'lantai', 'fasilitas'));
    }

    /**
     * Fetch kos detail data for the table via AJAX.
     */
    public function detailData($kos_id)
    {
        $kosDetails = KosDetail::with(['tipeKos', 'lantai'])
            ->where('kos_id', $kos_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                // Ensure fasilitas_ids is an array
                $item->fasilitas_ids = json_decode($item->fasilitas_ids, true) ?? [];
                $item->dekat_dengan = json_decode($item->dekat_dengan, true) ?? [];
                return $item;
            });

        return response()->json($kosDetails);
    }

    /**
     * Store a new kos detail record.
     */
    public function detailStore(Request $request, $kos_id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kos_id' => 'required|integer|exists:kos,id',
            'tipe_kos_id' => 'required|integer|exists:tipe_kos,id',
            'quantity' => 'required|integer|min:1',
            'lantai_id' => 'required|integer|exists:lantai,id',
            'fasilitas_ids' => 'required|array',
            'fasilitas_ids.*' => 'integer|exists:fasilitas,id',
            'deskripsi' => 'nullable|string',
            'jenis_kos' => 'required|in:Putra,Putri,Campur',
            'dekat_dengan' => 'nullable|array',
            'dekat_dengan.*' => 'string|max:255',
            'tipe_sewa' => 'required|in:Harian,Bulanan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();
        $data['fasilitas_ids'] = json_encode($request->fasilitas_ids);
        $data['dekat_dengan'] = $request->has('dekat_dengan') ? json_encode($request->dekat_dengan) : null;
        $kosDetail = KosDetail::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Kamar added successfully.',
            'data' => $kosDetail,
        ]);
    }

    /**
     * Update an existing kos detail record.
     */
    public function detailUpdate(Request $request, $id)
    {
        $kosDetail = KosDetail::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kos_id' => 'required|integer|exists:kos,id',
            'tipe_kos_id' => 'required|integer|exists:tipe_kos,id',
            'quantity' => 'required|integer|min:1',
            'lantai_id' => 'required|integer|exists:lantai,id',
            'fasilitas_ids' => 'required|array',
            'fasilitas_ids.*' => 'integer|exists:fasilitas,id',
            'deskripsi' => 'nullable|string',
            'jenis_kos' => 'required|in:Putra,Putri,Campur',
            'dekat_dengan' => 'nullable|array',
            'dekat_dengan.*' => 'string|max:255',
            'tipe_sewa' => 'required|in:Harian,Bulanan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();
        $data['fasilitas_ids'] = json_encode($request->fasilitas_ids);
        $data['dekat_dengan'] = $request->has('dekat_dengan') ? json_encode($request->dekat_dengan) : null;
        $kosDetail->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Kamar updated successfully.',
            'data' => $kosDetail,
        ]);
    }

    /**
     * Delete a kos detail record.
     */
    public function detailDestroy($id)
    {
        $kosDetail = KosDetail::findOrFail($id);
        $kosDetail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kamar deleted successfully.',
        ]);
    }

    /**
     * Display the gallery view for a specific kamar.
     */
    public function gallery($kos_id, $kamar_id)
    {
        $kos = Kos::findOrFail($kos_id);
        $kamar = KosDetail::findOrFail($kamar_id);
        return view('admin.gallerykos', compact('kos', 'kamar'));
    }

    /**
     * Fetch gallery data for the table via AJAX.
     */
    public function galleryData($kos_id, $kamar_id)
    {
        $gallery = GalleryKos::where('kamar_id', $kamar_id)->orderBy('created_at', 'desc')->get();
        return response()->json($gallery);
    }

    /**
     * Store a new image in the gallery.
     */
    public function galleryStore(Request $request, $kos_id, $kamar_id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $image = $request->file('image');
        $nama_file = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('gallery'), $nama_file);
        $url = '/gallery/' . $nama_file;

        $gallery = GalleryKos::create([
            'kamar_id' => $kamar_id,
            'nama_file' => $nama_file,
            'url' => $url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully.',
            'data' => $gallery,
        ]);
    }

    /**
     * Delete an image from the gallery.
     */
    public function galleryDestroy($kos_id, $kamar_id, $id)
    {
        $gallery = GalleryKos::where('kamar_id', $kamar_id)->findOrFail($id);
        unlink(public_path('gallery/' . $gallery->nama_file));
        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.',
        ]);
    }

    /**
     * Get all kos data with advanced search
     */
    public function getAllData(Request $request)
    {
        $search      = $request->query('search');
        $tipe        = $request->query('tipe');
        $daerah      = $request->query('daerah');
        $jenis       = $request->query('jenis');
        $nama_kamar  = $request->query('nama_kamar');
        $nama_kos    = $request->query('nama_kos');
        $start_date  = $request->query('start_date');
        $end_date    = $request->query('end_date');
        $perPage     = $request->query('per_page', 10);

        \Log::info('=== getAllData Request Params ===', [
            'search'     => $search,
            'tipe'       => $tipe,
            'daerah'     => $daerah,
            'jenis'      => $jenis,
            'nama_kamar' => $nama_kamar,
            'nama_kos'   => $nama_kos,
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'per_page'   => $perPage
        ]);

        $query = KosDetail::with(['kos.daerah', 'tipeKos', 'lantai', 'paketHarga'])
            ->orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('jenis_kos', 'like', "%{$search}%")
                  ->orWhereHas('kos', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%")
                          ->orWhere('alamat_kota', 'like', "%{$search}%")
                          ->orWhereHas('daerah', function ($q3) use ($search) {
                              $q3->where('nama', 'like', "%{$search}%");
                          });
                  })
                  ->orWhereHas('tipeKos', function ($q4) use ($search) {
                      $q4->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($tipe)   $query->whereHas('tipeKos', fn($q) => $q->where('nama', 'like', "%{$tipe}%"));
        if ($daerah) $query->whereHas('kos.daerah', fn($q) => $q->where('nama', 'like', "%{$daerah}%"));
        if ($jenis)  $query->where('jenis_kos', 'like', "%{$jenis}%");
        if ($nama_kamar) $query->where('nama', 'like', "%{$nama_kamar}%");
        if ($nama_kos)   $query->whereHas('kos', fn($q) => $q->where('nama', 'like', "%{$nama_kos}%"));

        if ($start_date && $end_date) {
            try {
                $startDate = Carbon::parse($start_date)->toDateString();
                $endDate   = Carbon::parse($end_date)->toDateString();

                \Log::info('Tanggal filter ter-parse', [
                    'startDate' => $startDate,
                    'endDate'   => $endDate
                ]);

                $query->whereHas('paketHarga', function ($q) use ($startDate, $endDate) {
                    $q->whereNotNull('ketersediaan')
                      ->whereRaw('EXISTS (
                          SELECT 1
                          FROM JSON_TABLE(
                              ketersediaan,
                              "$[*]" COLUMNS (
                                  start_date DATE PATH "$.start_date",
                                  end_date DATE PATH "$.end_date"
                              )
                          ) AS availability
                          WHERE availability.start_date <= ? 
                            AND availability.end_date >= ?
                      )', [$endDate, $startDate]);
                });

            } catch (\Exception $e) {
                \Log::error('Error parsing tanggal', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid start_date or end_date format',
                ], 400);
            }
        }

        $result = $query->paginate($perPage);
        \Log::info('Jumlah data setelah filter availability (query SQL)', ['count' => $result->total()]);

        if ($start_date && $end_date) {
            $result->setCollection(
                $result->getCollection()->filter(function ($item) use ($start_date, $end_date) {
                    $availabilityJson = optional($item->paketHarga)->ketersediaan;
                    \Log::info("Periksa ketersediaan untuk ID {$item->id}", [
                        'ketersediaan' => $availabilityJson
                    ]);

                    if (!$availabilityJson) {
                        \Log::info('❌ Tidak ada ketersediaan');
                        return false;
                    }

                    $availabilityArr = json_decode($availabilityJson, true);
                    if (!is_array($availabilityArr)) {
                        \Log::info('❌ Format ketersediaan tidak valid JSON array');
                        return false;
                    }

                    foreach ($availabilityArr as $periode) {
                        \Log::info('Cek periode', $periode);
                        if (
                            isset($periode['start_date'], $periode['end_date']) &&
                            $periode['start_date'] <= $end_date &&
                            $periode['end_date'] >= $start_date
                        ) {
                            \Log::info('✅ MATCH periode', $periode);
                            return true;
                        }
                    }

                    \Log::info('❌ Tidak ada periode cocok');
                    return false;
                })
            );
        }

        $result->setCollection(
            $result->getCollection()->map(function ($item) {
                $paketHarga = $item->paketHarga ? [
                    'perharian_harga' => $item->paketHarga->perharian_harga ?? null,
                    'perbulan_harga' => $item->paketHarga->perbulan_harga ?? null,
                    'pertigabulan_harga' => $item->paketHarga->pertigabulan_harga ?? null,
                    'perenambulan_harga' => $item->paketHarga->perenambulan_harga ?? null,
                    'pertahun_harga' => $item->paketHarga->pertahun_harga ?? null,
                    'ketersediaan' => $item->paketHarga->ketersediaan ?? [],
                ] : null;

                return [
                    'id'          => $item->id,
                    'nama_kamar'  => $item->nama,
                    'jenis_kos'   => $item->jenis_kos,
                    'nama_kos'    => $item->kos->nama ?? null,
                    'lokasi_kos'  => $item->kos->daerah->nama ?? null,
                    'tipe_kos'    => $item->tipeKos->nama ?? null,
                    'lantai'      => $item->lantai->nama ?? null,
                    'fasilitas'   => $item->fasilitas_ids ?? [],
                    'created_at'  => $item->created_at->format('d M Y H:i'),
                    'paket_harga' => $paketHarga,
                ];
            })
        );

        \Log::info('Jumlah data final setelah semua filter', ['count' => $result->count()]);

        return response()->json([
            'success' => true,
            'data'    => $result->items(),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
                'from'         => $result->firstItem(),
                'to'           => $result->lastItem(),
            ]
        ]);
    }

   /**
     * Fetch KosDetail by kos_id for dropdown
     */
    public function getKosDetails($kos_id)
    {
        $kosDetails = KosDetail::where('kos_id', $kos_id)
            ->select('id', 'nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kosDetails
        ]);
    }

    /**
     * Fetch PaketHarga by kamar_id for dropdown
     */
    public function getPaketHarga($kamar_id)
    {
        $paketHarga = PaketHarga::where('kamar_id', $kamar_id)
            ->select('id', 'nama', 'perharian_harga', 'perbulan_harga', 'pertigabulan_harga', 'perenambulan_harga', 'pertahun_harga')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $paketHarga
        ]);
    }
}