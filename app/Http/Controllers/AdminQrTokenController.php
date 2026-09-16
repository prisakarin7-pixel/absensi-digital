<?php
namespace App\Http\Controllers;

use App\Models\QrLocation;
use App\Models\QrToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminQrTokenController extends Controller
{
    public function index()
    {
        $tokens = QrToken::with('qrLocation')->latest()->get();
        $locations = QrLocation::where('status',1)->orderBy('nama_lokasi')->get();
        return view('qr-token.index', compact('tokens','locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'qr_location_id'=>['required','exists:qr_locations,id'],
            'tanggal'=>['required','date'],
            'waktu_mulai'=>['required','date_format:H:i'],
            'waktu_berakhir'=>['required','date_format:H:i','after:waktu_mulai'],
            'status'=>['nullable','boolean'],
        ]);
        $data['token'] = Str::random(64);
        $data['status'] = $request->boolean('status');
        QrToken::create($data);
        return back()->with('success','QR Token berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $token = QrToken::findOrFail($id);
        $data = $request->validate([
            'qr_location_id'=>['required','exists:qr_locations,id'],
            'tanggal'=>['required','date'],
            'waktu_mulai'=>['required','date_format:H:i'],
            'waktu_berakhir'=>['required','date_format:H:i','after:waktu_mulai'],
            'status'=>['nullable','boolean'],
        ]);
        $data['status'] = $request->boolean('status');
        $token->update($data);
        return back()->with('success','QR Token berhasil diperbarui.');
    }

    public function destroy($id)
    {
        QrToken::findOrFail($id)->delete();
        return back()->with('success','QR Token berhasil dihapus.');
    }
}
