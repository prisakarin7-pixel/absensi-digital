<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Menampilkan semua siswa
    public function index()
    {
        $students = Student::with(['user', 'class'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    // Menambahkan siswa
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nis' => 'required|string|max:50|unique:students,nis',
            'nama' => 'required|string|max:100',
            'class_id' => 'required|exists:classes,id',
        ]);

        $student = Student::create([
            'user_id' => $request->user_id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'class_id' => $request->class_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan',
            'data' => $student
        ], 201);
    }

    // Menampilkan detail siswa
    public function show($id)
    {
        $student = Student::with(['user', 'class', 'attendances'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    // Mengubah data siswa
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nis' => 'required|string|max:50|unique:students,nis,' . $id,
            'nama' => 'required|string|max:100',
            'class_id' => 'required|exists:classes,id',
        ]);

        $student->update([
            'user_id' => $request->user_id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'class_id' => $request->class_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diperbarui',
            'data' => $student
        ]);
    }

    // Menghapus siswa
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil dihapus'
        ]);
    }
}