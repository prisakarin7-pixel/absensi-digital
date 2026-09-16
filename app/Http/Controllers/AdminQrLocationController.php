<?php
namespace App\Http\Controllers;

use App\Models\QrLocation;
use Illuminate\Http\Request;

class AdminQrLocationController extends Controller
{
    public function index()
    {
        $locations = QrLocation::withCount(['qrTokens','attendances'])->latest()->get();
        return view('qr-location.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lokasi'=>['required','string','max:255'],
            'kode_lokasi'=>['required','string','max:255','unique:qr_locations,kode_lokasi'],
            'latitude'=>['nullable','numeric'],
            'longitude'=>['nullable','numeric'],
            'radius'=>['required','integer','min:1'],
            'status'=>['nullable','boolean'],
        ]);
        $data['status'] = $request->boolean('status');
        QrLocation::create($data);
        return back()->with('success','Lokasi QR berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $location = QrLocation::findOrFail($id);
        $data = $request->validate([
            'nama_lokasi'=>['required','string','max:255'],
            'kode_lokasi'=>['required','string','max:255','unique:qr_locations,kode_lokasi,'.$location->id],
            'latitude'=>['nullable','numeric'],
            'longitude'=>['nullable','numeric'],
            'radius'=>['required','integer','min:1'],
            'status'=>['nullable','boolean'],
        ]);
        $data['status'] = $request->boolean('status');
        $location->update($data);
        return back()->with('success','Lokasi QR berhasil diperbarui.');
    }

    public function destroy($id)
    {
        QrLocation::findOrFail($id)->delete();
        return back()->with('success','Lokasi QR berhasil dihapus.');
    }
}
