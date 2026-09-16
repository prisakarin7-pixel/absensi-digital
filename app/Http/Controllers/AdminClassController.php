<?php
namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::withCount('students')->latest()->get();
        return view('classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nama_kelas' => ['required','string','max:255']]);
        ClassModel::create($data);
        return back()->with('success','Data kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);
        $data = $request->validate(['nama_kelas' => ['required','string','max:255']]);
        $class->update($data);
        return back()->with('success','Data kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        if ($class->students()->exists()) {
            return back()->with('error','Kelas tidak dapat dihapus karena masih memiliki siswa.');
        }
        $class->delete();
        return back()->with('success','Data kelas berhasil dihapus.');
    }
}
