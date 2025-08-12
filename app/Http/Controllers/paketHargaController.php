<?php

namespace App\Http\Controllers;

use App\Models\KosDetail;
use App\Models\PaketHarga;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class paketHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\View\View
    {
        $paketHargas = PaketHarga::with(['kos', 'kamar'])->get();
        $koses = Kos::all();
        $kamars = KosDetail::all();

        return view('admin.master-paket-harga', compact('paketHargas', 'koses', 'kamars'));
    }

    /**
     * Return JSON data for the table.
     */
    public function data(): JsonResponse
    {
        $paketHargas = PaketHarga::with(['kos', 'kamar'])->get();

        return response()->json($paketHargas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'kamar_id' => 'required|exists:kos_detail,id',
            'perharian_harga' => 'nullable|integer|min:0',
            'perbulan_harga' => 'nullable|integer|min:0',
            'pertigabulan_harga' => 'nullable|integer|min:0',
            'perenambulan_harga' => 'nullable|integer|min:0',
            'pertahun_harga' => 'nullable|integer|min:0',
        ]);

        PaketHarga::create($validated);

        return response()->json(['success' => true, 'message' => 'Paket Harga berhasil ditambahkan']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaketHarga $paketHarga): JsonResponse
    {
        $validated = $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'kamar_id' => 'required|exists:kos_detail,id',
            'perharian_harga' => 'nullable|integer|min:0',
            'perbulan_harga' => 'nullable|integer|min:0',
            'pertigabulan_harga' => 'nullable|integer|min:0',
            'perenambulan_harga' => 'nullable|integer|min:0',
            'pertahun_harga' => 'nullable|integer|min:0',
        ]);

        $paketHarga->update($validated);

        return response()->json(['success' => true, 'message' => 'Paket Harga berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaketHarga $paketHarga): JsonResponse
    {
        $paketHarga->delete();

        return response()->json(['success' => true, 'message' => 'Paket Harga berhasil dihapus']);
    }
}