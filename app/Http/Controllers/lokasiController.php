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

    public function data(Request $request)
    {
        $search = $request->query('search');

        $query = Lokasi::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $lokasi = $query->orderBy('created_at', 'desc')->get()->map(function ($item) {
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        Lokasi::create($request->only('nama'));
        return redirect()->route('lokasi.index')->with('success', 'Location added successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($request->only('nama'));
        return redirect()->route('lokasi.index')->with('success', 'Location updated successfully');
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();
        return redirect()->route('lokasi.index')->with('success', 'Location deleted successfully');
    }

    
    public function getAll()
    {
        $lokasi = Lokasi::orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                'id'         => $item->id,
                'nama'       => $item->nama,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $lokasi
        ]);
    }
}
