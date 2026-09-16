<?php

namespace App\Http\Controllers;

use App\Models\QrLocation;
use Illuminate\Http\Request;

class QrLocationController extends Controller
{
    public function index()
    {
        $locations = QrLocation::withCount('qrTokens')
            ->latest()
            ->get();

        return view('qr-location.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'kode_lokasi' => 'required|string|max:100|unique:qr_locations,kode_lokasi',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:1',
            'status' => 'required|boolean',
        ]);

        QrLocation::create($validated);

        return redirect()
            ->route('qr-locations.index')
            ->with('success', 'Lokasi QR berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $location = QrLocation::findOrFail($id);

        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'kode_lokasi' => 'required|string|max:100|unique:qr_locations,kode_lokasi,' . $location->id,
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:1',
            'status' => 'required|boolean',
        ]);

        $location->update($validated);

        return redirect()
            ->route('qr-locations.index')
            ->with('success', 'Lokasi QR berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $location = QrLocation::findOrFail($id);

        if ($location->qrTokens()->exists()) {
            return redirect()
                ->route('qr-locations.index')
                ->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki QR Token.');
        }

        $location->delete();

        return redirect()
            ->route('qr-locations.index')
            ->with('success', 'Lokasi QR berhasil dihapus!');
    }
}