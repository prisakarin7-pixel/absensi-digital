<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::withCount('students')
            ->latest()
            ->get();

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:classes,nama_kelas',
        ]);

        ClassModel::create([
            'nama_kelas' => $validated['nama_kelas'],
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show($id)
    {
        $class = ClassModel::with('students')->findOrFail($id);

        return view('classes.show', compact('class'));
    }

    public function edit($id)
    {
        $class = ClassModel::findOrFail($id);

        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:classes,nama_kelas,' . $class->id,
        ]);

        $class->update([
            'nama_kelas' => $validated['nama_kelas'],
        ]);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);

        DB::transaction(function () use ($class) {
            $class->delete();
        });

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}