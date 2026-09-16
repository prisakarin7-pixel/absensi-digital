<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student.class','qrLocation'])->latest();

        if ($request->filled('tanggal')) $query->whereDate('tanggal',$request->tanggal);
        if ($request->filled('status')) $query->where('status',$request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', fn($q) => $q->where('nama','like',"%{$search}%")->orWhere('nis','like',"%{$search}%"));
        }

        $attendances = $query->paginate(10)->withQueryString();
        return view('attendance.index', compact('attendances'));
    }

    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return back()->with('success','Data absensi berhasil dihapus.');
    }
}
