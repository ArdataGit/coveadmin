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
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json($fasilitas);
    }

    /**
     * Store a new facility
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        Fasilitas::create($request->only('nama'));

        // Return JSON for AJAX or redirect for non-AJAX
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Facility added successfully']);
        }

        return redirect()->route('fasilitas.index')->with('success', 'Facility added successfully');
    }

    /**
     * Update an existing facility
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->update($request->only('nama'));

        // Return JSON for AJAX or redirect for non-AJAX
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Facility updated successfully']);
        }

        return redirect()->route('fasilitas.index')->with('success', 'Facility updated successfully');
    }

    /**
     * Delete a facility
     */
    public function destroy($id)
    {
        try {
            $fasilitas = Fasilitas::findOrFail($id);
            $fasilitas->delete();

            // Return JSON for AJAX or redirect for non-AJAX
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Facility deleted successfully']);
            }

            return redirect()->route('fasilitas.index')->with('success', 'Facility deleted successfully');
        } catch (\Exception $e) {
            // Return JSON for AJAX or redirect for non-AJAX
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete facility: ' . $e->getMessage()], 422);
            }

            return redirect()->route('fasilitas.index')->with('error', 'Failed to delete facility: ' . $e->getMessage());
        }
    }
}