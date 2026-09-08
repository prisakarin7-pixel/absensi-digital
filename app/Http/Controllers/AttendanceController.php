<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\QrToken;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Menampilkan data absensi
    public function index()
    {
        $attendances = Attendance::with([
            'student',
            'qrLocation'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    // Melakukan absensi
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'token' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Cari token QR
        $qrToken = QrToken::with('qrLocation')
            ->where('token', $request->token)
            ->first();

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR tidak ditemukan'
            ], 404);
        }

        // Cek status token
        if (!$qrToken->status) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR sudah tidak aktif'
            ], 400);
        }

        // Cek tanggal
        $now = Carbon::now();
        $tanggal = $now->format('Y-m-d');
        $waktu = $now->format('H:i:s');

        if ($qrToken->tanggal != $tanggal) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR tidak berlaku hari ini'
            ], 400);
        }

        // Cek waktu berlaku
        if (
            $waktu < $qrToken->waktu_mulai ||
            $waktu > $qrToken->waktu_berakhir
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR sudah di luar waktu berlaku'
            ], 400);
        }

        // Cek apakah siswa sudah absen hari ini
        $alreadyAbsent = Attendance::where('student_id', $request->student_id)
            ->where('tanggal', $tanggal)
            ->exists();

        if ($alreadyAbsent) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa sudah melakukan absensi hari ini'
            ], 400);
        }

        // Hitung jarak siswa dengan lokasi QR
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $qrToken->qrLocation->latitude,
            $qrToken->qrLocation->longitude
        );

        // Cek radius
        if ($distance > $qrToken->qrLocation->radius) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi berada di luar radius absensi',
                'distance' => round($distance, 2) . ' meter'
            ], 400);
        }

        // Simpan absensi
        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'status' => 'hadir',
            'qr_location_id' => $qrToken->qr_location_id,
            'token' => $request->token,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil',
            'data' => $attendance
        ], 201);
    }

    // Menampilkan detail absensi
    public function show($id)
    {
        $attendance = Attendance::with([
            'student',
            'qrLocation'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    // Menghapus data absensi
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil dihapus'
        ]);
    }

    // Menghitung jarak menggunakan koordinat GPS
    private function calculateDistance(
        $lat1,
        $lon1,
        $lat2,
        $lon2
    ) {
        $earthRadius = 6371000;

        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1)
            * cos($lat2)
            * sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}