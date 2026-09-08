<?php

namespace App\Http\Controllers;

use App\Models\QrToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrTokenController extends Controller
{
    // Menampilkan semua token
    public function index()
    {
        $tokens = QrToken::with('qrLocation')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tokens
        ]);
    }

    // Membuat token QR baru
    public function store(Request $request)
    {
        $request->validate([
            'qr_location_id' => 'required|exists:qr_locations,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            'status' => 'boolean',
        ]);

        $token = QrToken::create([
            'qr_location_id' => $request->qr_location_id,
            'token' => Str::random(64),
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'status' => $request->status ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token QR berhasil dibuat',
            'data' => $token
        ], 201);
    }

    // Menampilkan detail token
    public function show($id)
    {
        $token = QrToken::with('qrLocation')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $token
        ]);
    }

    // Mengubah token
    public function update(Request $request, $id)
    {
        $token = QrToken::findOrFail($id);

        $request->validate([
            'qr_location_id' => 'required|exists:qr_locations,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            'status' => 'boolean',
        ]);

        $token->update([
            'qr_location_id' => $request->qr_location_id,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'status' => $request->status ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token QR berhasil diperbarui',
            'data' => $token
        ]);
    }

    // Menghapus token
    public function destroy($id)
    {
        $token = QrToken::findOrFail($id);

        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token QR berhasil dihapus'
        ]);
    }
}