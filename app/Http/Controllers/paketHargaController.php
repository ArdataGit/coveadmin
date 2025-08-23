<?php

namespace App\Http\Controllers;

use App\Models\KosDetail;
use App\Models\PaketHarga;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

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
     * Store a newly created paket harga in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kos_id' => 'required|exists:kos,id',
            'kamar_id' => [
                'required',
                'exists:kos_detail,id',
                function ($attribute, $value, $fail) use ($request) {
                    $kamar = KosDetail::find($value);
                    if ($kamar && $kamar->kos_id != $request->kos_id) {
                        $fail('Kamar tidak terkait dengan kos yang dipilih.');
                    }
                },
            ],
            'nama' => 'required|string|max:255',
            'perharian_harga' => 'nullable|numeric|min:0',
            'perbulan_harga' => 'nullable|numeric|min:0',
            'pertigabulan_harga' => 'nullable|numeric|min:0',
            'perenambulan_harga' => 'nullable|numeric|min:0',
            'pertahun_harga' => 'nullable|numeric|min:0',
            'ketersediaan' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();
        if (isset($data['ketersediaan']) && is_array($data['ketersediaan'])) {
            $data['ketersediaan'] = json_encode($data['ketersediaan']);
        }

        $paketHarga = PaketHarga::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Paket Harga berhasil ditambahkan',
            'data' => $paketHarga
        ]);
    }

    /**
     * Update the specified paket harga in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaketHarga  $paketHarga
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, PaketHarga $paketHarga)
    {
        $validator = Validator::make($request->all(), [
            'kos_id' => 'required|exists:kos,id',
            'kamar_id' => [
                'required',
                'exists:kos_detail,id',
                function ($attribute, $value, $fail) use ($request) {
                    $kamar = KosDetail::find($value);
                    if ($kamar && $kamar->kos_id != $request->kos_id) {
                        $fail('Kamar tidak terkait dengan kos yang dipilih.');
                    }
                },
            ],
            'nama' => 'required|string|max:255',
            'perharian_harga' => 'nullable|numeric|min:0',
            'perbulan_harga' => 'nullable|numeric|min:0',
            'pertigabulan_harga' => 'nullable|numeric|min:0',
            'perenambulan_harga' => 'nullable|numeric|min:0',
            'pertahun_harga' => 'nullable|numeric|min:0',
            'ketersediaan' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $request->all();
        if (isset($data['ketersediaan']) && is_array($data['ketersediaan'])) {
            $data['ketersediaan'] = json_encode($data['ketersediaan']);
        }

        $paketHarga->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Paket Harga berhasil diupdate',
            'data' => $paketHarga
        ]);
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
