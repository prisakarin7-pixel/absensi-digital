<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TANGGAL HARI INI
        |--------------------------------------------------------------------------
        */
        $today = Carbon::today();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */

        // Total seluruh siswa
        $totalStudents = DB::table('students')->count();

        // Total seluruh kelas
        $totalClasses = DB::table('classes')->count();

        /*
         * Tabel teachers belum tersedia di database saat ini.
         * Untuk sementara nilainya 0.
         *
         * Setelah tabel teachers dibuat pada tahap Data Guru,
         * bagian ini akan kita sambungkan ke database.
         */
        $totalTeachers = 0;


        /*
        |--------------------------------------------------------------------------
        | DATA ABSENSI HARI INI
        |--------------------------------------------------------------------------
        */

        $attendanceToday = DB::table('attendance')
            ->whereDate('tanggal', $today)
            ->get();


        // Total absensi hari ini
        $totalAttendanceToday = $attendanceToday->count();


        /*
        |--------------------------------------------------------------------------
        | JUMLAH BERDASARKAN STATUS
        |--------------------------------------------------------------------------
        */

        $hadirToday = DB::table('attendance')
            ->whereDate('tanggal', $today)
            ->whereRaw('LOWER(status) = ?', ['hadir'])
            ->count();

        $izinToday = DB::table('attendance')
            ->whereDate('tanggal', $today)
            ->whereRaw('LOWER(status) = ?', ['izin'])
            ->count();

        $sakitToday = DB::table('attendance')
            ->whereDate('tanggal', $today)
            ->whereRaw('LOWER(status) = ?', ['sakit'])
            ->count();

        $alphaToday = DB::table('attendance')
            ->whereDate('tanggal', $today)
            ->whereRaw('LOWER(status) = ?', ['alpha'])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PERSENTASE KEHADIRAN
        |--------------------------------------------------------------------------
        |
        | Persentase dihitung berdasarkan jumlah siswa terdaftar.
        |
        | Contoh:
        | 95 siswa hadir dari 100 siswa = 95%
        |
        */

        $attendancePercentage = 0;

        if ($totalStudents > 0) {
            $attendancePercentage = round(
                ($hadirToday / $totalStudents) * 100
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ABSENSI TERBARU
        |--------------------------------------------------------------------------
        |
        | Mengambil beberapa absensi terbaru sekaligus dengan
        | nama siswa dan nama kelas.
        |
        */

        $recentAttendances = DB::table('attendance')
            ->join(
                'students',
                'attendance.student_id',
                '=',
                'students.id'
            )
            ->join(
                'classes',
                'students.class_id',
                '=',
                'classes.id'
            )
            ->select(
                'attendance.id',
                'attendance.tanggal',
                'attendance.waktu',
                'attendance.status',
                'students.nama as nama_siswa',
                'students.nis',
                'classes.nama_kelas'
            )
            ->orderByDesc('attendance.tanggal')
            ->orderByDesc('attendance.waktu')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('dashboard', compact(
            'today',

            'totalStudents',
            'totalClasses',
            'totalTeachers',

            'totalAttendanceToday',

            'hadirToday',
            'izinToday',
            'sakitToday',
            'alphaToday',

            'attendancePercentage',

            'recentAttendances'
        ));
    }
}