<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosDetail;
use App\Models\Lokasi;
use App\Models\TipeKos;
use App\Models\Lantai;
use App\Models\Fasilitas;
use App\Models\GalleryKos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class KosController extends Controller
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
        // dd( $kos);
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
            'dekat_dengan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();
        $data['fasilitas_ids'] = json_encode($request->fasilitas_ids);
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
            'dekat_dengan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();
        $data['fasilitas_ids'] = json_encode($request->fasilitas_ids);
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
     * Fetch gallery data for the table via AJAX.
     *//**
     * Display the gallery view for a specific kamar.
     */
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $image = $request->file('image');
        $nama_file = time() . '_' . $image->getClientOriginalName();
        // Store the image directly in public/gallery/
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
        // Delete the image from public/gallery/
        unlink(public_path('gallery/' . $gallery->nama_file));
        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.',
        ]);
    }
}