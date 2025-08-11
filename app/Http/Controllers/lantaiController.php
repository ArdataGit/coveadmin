<?php

namespace App\Http\Controllers;

use App\Models\Lantai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class lantaiController extends Controller
{
    public function index()
    {
        $lantai = Lantai::all();
        return view('admin.lantai', compact('lantai'));
    }

    public function data()
    {
        $lantai = Lantai::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });
        return response()->json($lantai);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        Lantai::create($request->only('nama'));
        return response()->json(['success' => true, 'message' => 'Floor added successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $lantai = Lantai::findOrFail($id);
        $lantai->update($request->only('nama'));
        return response()->json(['success' => true, 'message' => 'Floor updated successfully']);
    }

    public function destroy($id)
    {
        $lantai = Lantai::findOrFail($id);
        $lantai->delete();
        return response()->json(['success' => true, 'message' => 'Floor deleted successfully']);
    }
}