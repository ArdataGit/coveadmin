<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class lokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();
        return view('admin.lokasi', compact('lokasi'));
    }

    public function data()
    {
        $lokasi = Lokasi::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });
        return response()->json($lokasi);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        Lokasi::create($request->only('nama'));
        return response()->json(['success' => true, 'message' => 'Location added successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($request->only('nama'));
        return response()->json(['success' => true, 'message' => 'Location updated successfully']);
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();
        return response()->json(['success' => true, 'message' => 'Location deleted successfully']);
    }
}