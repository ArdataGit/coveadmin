<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FasilitasController extends Controller
{
    /**
     * Display a listing of facilities
     */
    public function index()
    {
        $fasilitas = Fasilitas::all();
        return view('admin.fasilitas', compact('fasilitas'));
    }

    /**
     * Return facility data for AJAX table
     */
    public function data(Request $request)
    {
        $query = Fasilitas::query();

        // Apply search filter if provided
        if ($search = $request->query('search')) {
            $query->where('nama', 'LIKE', "%{$search}%");
        }

        $fasilitas = $query->orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'image' => $item->image,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json($fasilitas);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nama'  => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
    }

    $data = $request->only('nama');

    if ($request->hasFile('image')) {
        $file     = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('fasilitas'), $filename);

        $data['image'] = 'fasilitas/' . $filename; // simpan path relatif
    }

    Fasilitas::create($data);

    return response()->json(['success' => true, 'message' => 'Facility added successfully']);
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'nama'  => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
    }

    $fasilitas = Fasilitas::findOrFail($id);
    $data = $request->only('nama');

    if ($request->hasFile('image')) {
        // hapus file lama kalau ada
        if ($fasilitas->image && file_exists(public_path($fasilitas->image))) {
            unlink(public_path($fasilitas->image));
        }

        $file     = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('fasilitas'), $filename);

        $data['image'] = 'fasilitas/' . $filename;
    }

    $fasilitas->update($data);

    return response()->json(['success' => true, 'message' => 'Facility updated successfully']);
}

public function destroy($id)
{
    $fasilitas = Fasilitas::findOrFail($id);

    if ($fasilitas->image && file_exists(public_path($fasilitas->image))) {
        unlink(public_path($fasilitas->image));
    }

    $fasilitas->delete();

    return response()->json(['success' => true, 'message' => 'Facility deleted successfully']);
}


    public function getAll()
    {
        $fasilitas = Fasilitas::orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                'id'         => $item->id,
                'nama'       => $item->nama,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $fasilitas
        ]);
    }
}