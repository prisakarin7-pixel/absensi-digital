<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassModel as Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['class', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $classes = Classes::all();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = Classes::all();

        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50|unique:students,nis',
            'class_id' => 'required|exists:classes,id',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => 2,
            ]);

            Student::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'nama' => $validated['nama'],
                'class_id' => $validated['class_id'],
            ]);
        });

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $classes = Classes::all();

        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50|unique:students,nis,' . $student->id,
            'class_id' => 'required|exists:classes,id',
        ]);

        DB::transaction(function () use ($student, $validated) {
            $student->update([
                'nama' => $validated['nama'],
                'nis' => $validated['nis'],
                'class_id' => $validated['class_id'],
            ]);

            if ($student->user) {
                $student->user->update([
                    'name' => $validated['nama'],
                ]);
            }
        });

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $student = Student::with('user')->findOrFail($id);

        DB::transaction(function () use ($student) {
            if ($student->user) {
                $student->user->delete();
            }

            $student->delete();
        });

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil dihapus!');
    }
}