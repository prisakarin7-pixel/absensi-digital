@extends('layout.app')

@section('title', 'Data Siswa | Absensi Digital')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <p class="text-[10px] font-bold text-olive tracking-[1.5px] uppercase mb-1">MANAJEMEN KESISWAAN</p>
        <h1 class="text-3xl font-bold text-dark-green">Data Siswa</h1>
    </div>

    <div class="bg-light-sage px-4 py-3 rounded-[16px] flex items-center gap-3">
        <div class="w-10 h-10 rounded-[12px] bg-olive text-white flex items-center justify-center">
            <i data-lucide="calendar-days" class="w-4 h-4"></i>
        </div>

        <div>
            <span class="block text-[9px] text-muted-text">Hari ini</span>
            <strong class="text-xs text-dark-green font-bold">
                {{ \Carbon\Carbon::now()->format('d M Y') }}
            </strong>
        </div>
    </div>
</div>


{{-- NOTIFIKASI SUKSES --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-sage text-dark-green rounded-[14px] font-bold text-xs">
        {{ session('success') }}
    </div>
@endif


{{-- ERROR VALIDASI --}}
@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-[14px] text-xs border border-red-100">
        <div class="font-bold mb-1">Data belum dapat disimpan:</div>

        <ul class="list-disc ml-4 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- FILTER & SEARCH BAR --}}
<form method="GET" action="{{ route('students.index') }}" class="bg-white p-5 rounded-[23px] border border-border-color shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">

    <div class="flex gap-3 flex-1 min-w-[280px]">

        <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text w-4 h-4"></i>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari NIS, Nama Siswa..."
                class="w-full pl-10 pr-4 py-2.5 bg-light-sage/40 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
            >
        </div>

        <select
            name="class_id"
            onchange="this.form.submit()"
            class="bg-light-sage/40 border border-border-color rounded-[14px] px-3 py-2.5 text-xs text-text-main focus:outline-none focus:border-olive"
        >
            <option value="">Semua Kelas</option>

            @foreach($classes as $c)
                <option
                    value="{{ $c->id }}"
                    {{ request('class_id') == $c->id ? 'selected' : '' }}
                >
                    {{ $c->nama_kelas }}
                </option>
            @endforeach
        </select>

    </div>

    <button
        type="button"
        onclick="openModal('modalTambahSiswa')"
        class="bg-olive hover:bg-dark-green text-white px-5 py-2.5 rounded-[14px] text-xs font-bold flex items-center gap-2 transition shadow-sm"
    >
        <i data-lucide="plus" class="w-4 h-4"></i>
        Tambah Siswa
    </button>

</form>


{{-- TABEL DATA SISWA --}}
<div class="bg-white rounded-[23px] border border-border-color shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="border-b border-border-color text-[9px] font-semibold text-muted-text uppercase bg-light-sage/30">
                    <th class="p-4">Siswa</th>
                    <th class="p-4">NIS</th>
                    <th class="p-4">Kelas</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-border-color/50 text-xs text-text-main">

                @forelse($students as $student)

                    @php
                        $nama = $student->nama ?? 'Siswa';
                        $initials = collect(preg_split('/\s+/', trim($nama)))
                            ->take(2)
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->implode('');
                    @endphp

                    <tr class="hover:bg-light-sage/20 transition">

                        <td class="p-4">

                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9 rounded-[11px] bg-sage text-dark-green font-bold flex items-center justify-center text-xs uppercase">
                                    {{ $initials ?: 'S' }}
                                </div>

                                <div>
                                    <strong class="block text-dark-green text-xs">
                                        {{ $student->nama }}
                                    </strong>

                                    <span class="text-[9px] text-muted-text">
                                        {{ $student->user->email ?? '-' }}
                                    </span>
                                </div>

                            </div>

                        </td>


                        <td class="p-4 font-mono text-xs">
                            {{ $student->nis }}
                        </td>


                        <td class="p-4">

                            <span class="px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-semibold">
                                {{ $student->class->nama_kelas ?? 'Tanpa Kelas' }}
                            </span>

                        </td>


                        <td class="p-4 text-center">

                            <div class="flex items-center justify-center gap-1">

                                <button
                                    type="button"
                                    onclick="editSiswa(
                                        '{{ $student->id }}',
                                        @js($student->nama),
                                        @js($student->nis),
                                        '{{ $student->class_id }}'
                                    )"
                                    class="p-2 text-muted-text hover:text-olive rounded-[10px] hover:bg-light-sage transition"
                                    title="Edit siswa"
                                >
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </button>


                                <form
                                    action="{{ route('students.destroy', $student->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="p-2 text-muted-text hover:text-red-600 rounded-[10px] hover:bg-light-sage transition"
                                        title="Hapus siswa"
                                    >
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="4"
                            class="p-6 text-center text-muted-text"
                        >
                            Belum ada data siswa di database.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="p-4">
        {{ $students->links() }}
    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL TAMBAH SISWA --}}
{{-- ========================================================= --}}

<div
    id="modalTambahSiswa"
    class="fixed inset-0 bg-dark-green/40 backdrop-blur-sm z-50 flex items-center justify-center hidden"
>

    <div class="bg-white rounded-[23px] w-full max-w-md p-6 shadow-xl relative border border-border-color">

        <button
            type="button"
            onclick="closeModal('modalTambahSiswa')"
            class="absolute top-5 right-5 text-muted-text hover:text-dark-green"
        >
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>


        <h3 class="text-base font-bold text-dark-green mb-4 border-b border-border-color pb-3">
            Tambah Data Siswa
        </h3>


        <form
            action="{{ route('students.store') }}"
            method="POST"
            class="space-y-3"
        >

            @csrf


            <div>

                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    placeholder="Masukkan nama siswa"
                >

            </div>


            <div class="grid grid-cols-2 gap-3">

                <div>

                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        NIS
                    </label>

                    <input
                        type="text"
                        name="nis"
                        value="{{ old('nis') }}"
                        required
                        class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                        placeholder="12345"
                    >

                </div>


                <div>

                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        Kelas
                    </label>

                    <select
                        name="class_id"
                        required
                        class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    >

                        <option value="">
                            Pilih Kelas
                        </option>

                        @foreach($classes as $c)

                            <option
                                value="{{ $c->id }}"
                                {{ old('class_id') == $c->id ? 'selected' : '' }}
                            >
                                {{ $c->nama_kelas }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <div>

                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Email (Untuk Akun)
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    placeholder="siswa@gmail.com"
                >

            </div>


            <div>

                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    placeholder="••••••••"
                >

            </div>


            <div class="flex justify-end gap-2 pt-3 border-t border-border-color">

                <button
                    type="button"
                    onclick="closeModal('modalTambahSiswa')"
                    class="px-4 py-2 bg-light-sage text-dark-green rounded-[14px] text-xs font-bold hover:bg-sage transition"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-olive text-white rounded-[14px] text-xs font-bold hover:bg-dark-green transition"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL EDIT SISWA --}}
{{-- ========================================================= --}}

<div
    id="modalEditSiswa"
    class="fixed inset-0 bg-dark-green/40 backdrop-blur-sm z-50 flex items-center justify-center hidden"
>

    <div class="bg-white rounded-[23px] w-full max-w-md p-6 shadow-xl relative border border-border-color">

        <button
            type="button"
            onclick="closeModal('modalEditSiswa')"
            class="absolute top-5 right-5 text-muted-text hover:text-dark-green"
        >
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>


        <h3 class="text-base font-bold text-dark-green mb-4 border-b border-border-color pb-3">
            Edit Data Siswa
        </h3>


        <form
            id="formEditSiswa"
            method="POST"
            class="space-y-3"
        >

            @csrf
            @method('PUT')


            <div>

                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    id="edit_nama"
                    name="nama"
                    required
                    class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >

            </div>


            <div class="grid grid-cols-2 gap-3">

                <div>

                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        NIS
                    </label>

                    <input
                        type="text"
                        id="edit_nis"
                        name="nis"
                        required
                        class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    >

                </div>


                <div>

                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        Kelas
                    </label>

                    <select
                        id="edit_class_id"
                        name="class_id"
                        required
                        class="w-full px-3.5 py-2 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    >

                        @foreach($classes as $c)

                            <option value="{{ $c->id }}">
                                {{ $c->nama_kelas }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <div class="flex justify-end gap-2 pt-3 border-t border-border-color">

                <button
                    type="button"
                    onclick="closeModal('modalEditSiswa')"
                    class="px-4 py-2 bg-light-sage text-dark-green rounded-[14px] text-xs font-bold hover:bg-sage transition"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-olive text-white rounded-[14px] text-xs font-bold hover:bg-dark-green transition"
                >
                    Update
                </button>

            </div>

        </form>

    </div>

</div>


@push('scripts')

<script>
    function openModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function editSiswa(id, nama, nis, classId) {
        const form = document.getElementById('formEditSiswa');

        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nis').value = nis;
        document.getElementById('edit_class_id').value = classId;

        form.action = "{{ url('/students') }}/" + id;

        openModal('modalEditSiswa');
    }

    document.addEventListener('DOMContentLoaded', function () {

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        @if($errors->any())
            openModal('modalTambahSiswa');
        @endif

    });
</script>

@endpush

@endsection