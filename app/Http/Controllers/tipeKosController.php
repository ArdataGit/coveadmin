<?php

namespace App\Http\Controllers;

use App\Models\TipeKos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class tipeKosController extends Controller
{
    public function index()
    {
        $tipeKos = TipeKos::all();
        return view('admin.tipe-kos', compact('tipeKos'));
    }

    public function data()
    {
        $tipeKos = TipeKos::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });
        return response()->json($tipeKos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        TipeKos::create($request->only('nama'));
        return response()->json(['success' => true, 'message' => 'Room type added successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $tipeKos = TipeKos::findOrFail($id);
        $tipeKos->update($request->only('nama'));
        return response()->json(['success' => true, 'message' => 'Room type updated successfully']);
    }

    public function destroy($id)
    {
        $tipeKos = TipeKos::findOrFail($id);
        $tipeKos->delete();
        return response()->json(['success' => true, 'message' => 'Room type deleted successfully']);
    }
}