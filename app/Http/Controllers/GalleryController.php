<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    /**
     * Display the gallery view for a specific kos.
     */
    public function gallerykos($kos_id)
    {
        Log::info('gallerykos called', ['kos_id' => $kos_id]);
        $kos = Kos::findOrFail($kos_id);
        return view('admin.gallery', compact('kos'));
    }

    /**
     * Fetch gallery data for the table via AJAX.
     */
    public function galleryKosData($kos_id)
    {
        Log::info('galleryKosData called', ['kos_id' => $kos_id]);
        $gallery = Gallery::where('kos_id', $kos_id)->orderBy('created_at', 'desc')->get();
        Log::info('galleryKosData result count', ['count' => $gallery->count()]);
        return response()->json($gallery);
    }

    /**
     * Store a new image in the gallery.
     */
    public function galleryKosStore(Request $request, $kos_id)
    {
        Log::info('galleryKosStore called', ['kos_id' => $kos_id]);
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $image = $request->file('image');
        $nama_file = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('gallery/kos/' . $kos_id), $nama_file);
        $url = '/gallery/kos/' . $kos_id . '/' . $nama_file;

        $gallery = Gallery::create([
            'kos_id' => $kos_id,
            'nama_file' => $nama_file,
            'url' => $url,
        ]);

        Log::info('Image uploaded successfully', ['gallery_id' => $gallery->id]);
        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully.',
            'data' => $gallery,
        ]);
    }

    /**
     * Delete an image from the gallery.
     */
    public function galleryKosDestroy($kos_id, $id)
    {
        Log::info('galleryKosDestroy called', ['kos_id' => $kos_id, 'gallery_id' => $id]);
        $gallery = Gallery::where('kos_id', $kos_id)->findOrFail($id);
        
        $file_path = public_path('gallery/kos/' . $kos_id . '/' . $gallery->nama_file);
        if (file_exists($file_path)) {
            unlink($file_path);
        } else {
            Log::warning('File not found for deletion', ['file_path' => $file_path]);
        }

        $gallery->delete();
        Log::info('Image deleted successfully', ['gallery_id' => $id]);
        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.',
        ]);
    }
}