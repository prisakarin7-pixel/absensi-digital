<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\QrToken;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        $query = Attendance::with([
            'student.class',
            'qrLocation'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();

        $hadirToday = Attendance::whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        $izinToday = Attendance::whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->count();

        $sakitToday = Attendance::whereDate('tanggal', $today)
            ->where('status', 'sakit')
            ->count();

        $alphaToday = Attendance::whereDate('tanggal', $today)
            ->where('status', 'alpha')
            ->count();

        $totalAttendanceToday = Attendance::whereDate('tanggal', $today)
            ->count();

        $attendancePercentage = $totalAttendanceToday > 0
            ? round(($hadirToday / $totalAttendanceToday) * 100)
            : 0;

        return view('attendance.index', compact(
            'attendances',
            'hadirToday',
            'izinToday',
            'sakitToday',
            'alphaToday',
            'totalAttendanceToday',
            'attendancePercentage'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'token' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $qrToken = QrToken::with('qrLocation')
            ->where('token', $validated['token'])
            ->first();

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR tidak ditemukan'
            ], 404);
        }

        if (!$qrToken->status) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR sudah tidak aktif'
            ], 400);
        }

        if (!$qrToken->qrLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi QR tidak ditemukan'
            ], 404);
        }

        $now = Carbon::now();
        $tanggal = $now->format('Y-m-d');
        $waktu = $now->format('H:i:s');

        if ($qrToken->tanggal != $tanggal) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR tidak berlaku hari ini'
            ], 400);
        }

        if (
            $waktu < $qrToken->waktu_mulai ||
            $waktu > $qrToken->waktu_berakhir
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR sudah di luar waktu berlaku'
            ], 400);
        }

        $alreadyAbsent = Attendance::where('student_id', $validated['student_id'])
            ->where('tanggal', $tanggal)
            ->exists();

        if ($alreadyAbsent) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa sudah melakukan absensi hari ini'
            ], 400);
        }

        $distance = $this->calculateDistance(
            $validated['latitude'],
            $validated['longitude'],
            $qrToken->qrLocation->latitude,
            $qrToken->qrLocation->longitude
        );

        if ($distance > $qrToken->qrLocation->radius) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi berada di luar radius absensi',
                'distance' => round($distance, 2) . ' meter'
            ], 400);
        }

        $attendance = Attendance::create([
            'student_id' => $validated['student_id'],
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'status' => 'hadir',
            'qr_location_id' => $qrToken->qr_location_id,
            'token' => $validated['token'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil',
            'data' => $attendance
        ], 201);
    }

    public function show($id)
    {
        $attendance = Attendance::with([
            'student.class',
            'qrLocation'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->delete();

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Data absensi berhasil dihapus!');
    }

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