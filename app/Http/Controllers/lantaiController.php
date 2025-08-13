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

    public function data(Request $request)
    {
        $search = $request->query('search');

        $query = Lantai::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $lantai = $query->orderBy('created_at', 'desc')->get()->map(function ($item) {
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        Lantai::create($request->only('nama'));
        return redirect()->route('lantai.index')->with('success', 'Floor added successfully');
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

        $lantai = Lantai::findOrFail($id);
        $lantai->update($request->only('nama'));
        return redirect()->route('lantai.index')->with('success', 'Floor updated successfully');
    }

    public function destroy($id)
    {
        $lantai = Lantai::findOrFail($id);
        $lantai->delete();
        return redirect()->route('lantai.index')->with('success', 'Floor deleted successfully');
    }

    public function getAll()
    {
        $lantai = Lantai::orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                'id'         => $item->id,
                'nama'       => $item->nama,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $lantai
        ]);
    }
}