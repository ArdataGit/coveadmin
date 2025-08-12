<?php

namespace App\Http\Controllers;

use App\Models\TipeKos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class tipeKosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        if ($search) {
            $tipeKos = TipeKos::where('nama', 'like', '%' . $search . '%')->get();
        } else {
            $tipeKos = TipeKos::all();
        }

        return view('admin.tipe-kos', compact('tipeKos', 'search'));
    }

    public function data(Request $request)
    {
        $search = $request->query('search');

        $query = TipeKos::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $tipeKos = $query->get()->map(function ($item) {
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
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TipeKos::create($request->only('nama'));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Room type added successfully']);
        }

        return redirect()->route('tipeKos.index')->with('success', 'Room type added successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tipeKos = TipeKos::findOrFail($id);
        $tipeKos->update($request->only('nama'));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Room type updated successfully']);
        }

        return redirect()->route('tipeKos.index')->with('success', 'Room type updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $tipeKos = TipeKos::findOrFail($id);
        $tipeKos->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Room type deleted successfully']);
        }

        return redirect()->route('tipeKos.index')->with('success', 'Room type deleted successfully');
    }
}
