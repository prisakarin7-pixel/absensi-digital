<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
        ]);

        $class = ClassModel::create([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $class
        ], 201);
    }

    public function show($id)
    {
        $class = ClassModel::with('students')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $class
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
        ]);

        $class = ClassModel::findOrFail($id);

        $class->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diperbarui',
            'data' => $class
        ]);
    }

    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);

        $class->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus'
        ]);
    }
}