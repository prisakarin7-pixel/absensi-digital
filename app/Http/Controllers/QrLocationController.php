<?php

namespace App\Http\Controllers;

use App\Models\QrLocation;
use Illuminate\Http\Request;

class QrLocationController extends Controller
{
    // Menampilkan semua lokasi QR
    public function index()
    {
        $locations = QrLocation::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    // Menambahkan lokasi QR
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:100',
            'kode_lokasi' => 'required|string|max:50|unique:qr_locations,kode_lokasi',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        $location = QrLocation::create([
            'nama_lokasi' => $request->nama_lokasi,
            'kode_lokasi' => $request->kode_lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'status' => $request->status ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi QR berhasil ditambahkan',
            'data' => $location
        ], 201);
    }

    // Menampilkan detail lokasi QR
    public function show($id)
    {
        $location = QrLocation::with('qrTokens')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }

    // Mengubah lokasi QR
    public function update(Request $request, $id)
    {
        $location = QrLocation::findOrFail($id);

        $request->validate([
            'nama_lokasi' => 'required|string|max:100',
            'kode_lokasi' => 'required|string|max:50|unique:qr_locations,kode_lokasi,' . $id,
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        $location->update([
            'nama_lokasi' => $request->nama_lokasi,
            'kode_lokasi' => $request->kode_lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'status' => $request->status ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi QR berhasil diperbarui',
            'data' => $location
        ]);
    }

    // Menghapus lokasi QR
    public function destroy($id)
    {
        $location = QrLocation::findOrFail($id);

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lokasi QR berhasil dihapus'
        ]);
    }
}