@extends('layout.app')

@section('title', 'Data Kelas | Absensi Digital')

@section('content')

<!-- TOPBAR -->
<div class="flex justify-between items-center mb-8">
    <div>
        <p class="text-[10px] font-bold text-olive tracking-[1.5px] uppercase mb-1">MANAJEMEN AKADEMIK</p>
        <h1 class="text-3xl font-bold text-dark-green">Data Kelas</h1>
        <p class="text-xs text-muted-text mt-1">Kelola daftar kelas yang tersimpan pada database.</p>
    </div>

    <div class="bg-light-sage px-4 py-3 rounded-[16px] flex items-center gap-3">
        <div class="w-10 h-10 rounded-[12px] bg-olive text-white flex items-center justify-center">
            <i class="fa-regular fa-calendar text-base"></i>
        </div>
        <div>
            <span class="block text-[9px] text-muted-text">Hari ini</span>
            <strong class="text-xs text-dark-green font-bold">
                {{ \Carbon\Carbon::now()->format('d M Y') }}
            </strong>
        </div>
    </div>
</div>

<!-- NOTIFIKASI SUKSES -->
@if(session('success'))
<div class="mb-4 p-4 bg-sage text-dark-green rounded-[14px] font-bold text-xs">
    {{ session('success') }}
</div>
@endif

<!-- ERROR VALIDASI -->
@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-100 text-red-700 rounded-[14px] text-xs">
    <div class="font-bold mb-1">Data belum dapat diproses:</div>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- TOOLBAR -->
<div class="bg-white p-5 rounded-[23px] border border-border-color shadow-sm mb-6 flex flex-wrap gap-4 items-center justify-between">
    <div>
        <span class="block text-[9px] font-bold text-olive tracking-[1.5px] uppercase mb-1">
            DATA MASTER
        </span>
        <h2 class="text-base font-bold text-dark-green">
            Daftar Kelas
        </h2>
    </div>

    <button
        type="button"
        onclick="openModal('modalTambahKelas')"
        class="bg-olive hover:bg-dark-green text-white px-5 py-2.5 rounded-[14px] text-xs font-bold flex items-center gap-2 transition shadow-sm"
    >
        <i class="fa-solid fa-plus"></i>
        Tambah Kelas
    </button>
</div>

<!-- TABEL DATA KELAS -->
<div class="bg-white rounded-[23px] border border-border-color shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="border-b border-border-color text-[9px] font-semibold text-muted-text uppercase bg-light-sage/30">
                    <th class="p-4 w-16">No</th>
                    <th class="p-4">Nama Kelas</th>
                    <th class="p-4">Jumlah Siswa</th>
                    <th class="p-4">Dibuat</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-border-color/50 text-xs text-text-main">

                @forelse($classes as $i => $class)

                <tr class="hover:bg-light-sage/20 transition">

                    <!-- NOMOR -->
                    <td class="p-4 text-muted-text font-semibold">
                        {{ $i + 1 }}
                    </td>

                    <!-- NAMA KELAS -->
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-[11px] bg-sage text-dark-green font-bold flex items-center justify-center">
                                <i class="fa-solid fa-school"></i>
                            </div>

                            <div>
                                <strong class="block text-dark-green text-xs">
                                    {{ $class->nama_kelas }}
                                </strong>
                                <span class="text-[9px] text-muted-text">
                                    Data kelas
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- JUMLAH SISWA -->
                    <td class="p-4">
                        <span class="px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-semibold inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-user-group text-[9px]"></i>
                            {{ $class->students_count }} siswa
                        </span>
                    </td>

                    <!-- TANGGAL -->
                    <td class="p-4 text-xs text-muted-text">
                        {{ optional($class->created_at)->format('d M Y') ?? '-' }}
                    </td>

                    <!-- AKSI -->
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-1">

                            <!-- EDIT -->
                            <button
                                type="button"
                                onclick="editKelas({{ $class->id }}, @js($class->nama_kelas))"
                                class="p-2 text-muted-text hover:text-olive rounded-[10px] hover:bg-light-sage transition"
                                title="Edit"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- HAPUS -->
                            <form
                                action="{{ route('classes.destroy', $class->id) }}"
                                method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="p-2 text-muted-text hover:text-red-600 rounded-[10px] hover:bg-light-sage transition"
                                    title="Hapus"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="p-10 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-[14px] bg-light-sage text-olive flex items-center justify-center mb-3">
                                <i class="fa-solid fa-school text-lg"></i>
                            </div>

                            <strong class="block text-dark-green text-xs font-bold mb-1">
                                Belum ada data kelas
                            </strong>

                            <span class="text-[10px] text-muted-text">
                                Tambahkan kelas melalui tombol Tambah Kelas.
                            </span>
                        </div>
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH KELAS -->
<div
    id="modalTambahKelas"
    class="fixed inset-0 bg-dark-green/40 backdrop-blur-sm z-50 flex items-center justify-center hidden"
>
    <div class="bg-white rounded-[23px] w-full max-w-md p-6 shadow-xl relative border border-border-color">

        <button
            type="button"
            onclick="closeModal('modalTambahKelas')"
            class="absolute top-5 right-5 text-muted-text hover:text-dark-green transition"
        >
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h3 class="text-base font-bold text-dark-green mb-4 border-b border-border-color pb-3">
            Tambah Data Kelas
        </h3>

        <form
            action="{{ route('classes.store') }}"
            method="POST"
            class="space-y-4"
        >
            @csrf

            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Nama Kelas
                </label>

                <input
                    type="text"
                    name="nama_kelas"
                    required
                    maxlength="100"
                    value="{{ old('nama_kelas') }}"
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    placeholder="Contoh: XII RPL 1"
                >
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-border-color">
                <button
                    type="button"
                    onclick="closeModal('modalTambahKelas')"
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

<!-- MODAL EDIT KELAS -->
<div
    id="modalEditKelas"
    class="fixed inset-0 bg-dark-green/40 backdrop-blur-sm z-50 flex items-center justify-center hidden"
>
    <div class="bg-white rounded-[23px] w-full max-w-md p-6 shadow-xl relative border border-border-color">

        <button
            type="button"
            onclick="closeModal('modalEditKelas')"
            class="absolute top-5 right-5 text-muted-text hover:text-dark-green transition"
        >
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h3 class="text-base font-bold text-dark-green mb-4 border-b border-border-color pb-3">
            Edit Data Kelas
        </h3>

        <form
            id="formEditKelas"
            method="POST"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Nama Kelas
                </label>

                <input
                    type="text"
                    id="edit_nama_kelas"
                    name="nama_kelas"
                    required
                    maxlength="100"
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    placeholder="Masukkan nama kelas"
                >
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-border-color">
                <button
                    type="button"
                    onclick="closeModal('modalEditKelas')"
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

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editKelas(id, nama) {
        document.getElementById('formEditKelas').action = '/classes/' + id;
        document.getElementById('edit_nama_kelas').value = nama;
        openModal('modalEditKelas');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeModal('modalTambahKelas');
            closeModal('modalEditKelas');
        }
    });
</script>

@endsection