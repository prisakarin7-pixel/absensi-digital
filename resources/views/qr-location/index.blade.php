@extends('layout.app')

@section('title', 'Lokasi QR | Absensi Digital')

@section('content')

<!-- TOPBAR -->
<div class="flex justify-between items-center mb-8">
    <div>
        <p class="text-[10px] font-bold text-olive tracking-[1.5px] uppercase mb-1">
            PENGATURAN ABSENSI
        </p>
        <h1 class="text-3xl font-bold text-dark-green">
            Lokasi QR
        </h1>
        <p class="text-xs text-muted-text mt-1">
            Kelola lokasi yang digunakan untuk validasi absensi berbasis GPS.
        </p>
    </div>

    <div class="bg-light-sage px-4 py-3 rounded-[16px] flex items-center gap-3">
        <div class="w-10 h-10 rounded-[12px] bg-olive text-white flex items-center justify-center">
            <i class="fa-solid fa-location-dot text-base"></i>
        </div>

        <div>
            <span class="block text-[9px] text-muted-text">
                Hari ini
            </span>
            <strong class="text-xs text-dark-green font-bold">
                {{ date('d M Y') }}
            </strong>
        </div>
    </div>
</div>

<!-- NOTIFIKASI -->
@if(session('success'))
    <div class="mb-5 p-4 bg-sage text-dark-green rounded-[14px] text-xs font-bold">
        <i class="fa-solid fa-circle-check mr-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-5 p-4 bg-red-50 text-red-600 rounded-[14px] text-xs font-bold">
        <i class="fa-solid fa-circle-exclamation mr-2"></i>
        {{ session('error') }}
    </div>
@endif

<!-- HEADER CARD -->
<div class="bg-white rounded-[23px] border border-border-color shadow-sm overflow-hidden">

    <div class="p-5 flex flex-wrap items-center justify-between gap-4 border-b border-border-color">
        <div>
            <span class="block text-[9px] font-bold text-olive tracking-[1.2px] uppercase">
                DATA MASTER
            </span>

            <h2 class="text-base font-bold text-dark-green">
                Daftar Lokasi Absensi
            </h2>

            <p class="text-[10px] text-muted-text mt-1">
                Lokasi digunakan sebagai titik validasi GPS saat siswa melakukan absensi.
            </p>
        </div>

        <button
            type="button"
            onclick="openModal('modalTambahLokasi')"
            class="bg-olive hover:bg-dark-green text-white px-5 py-2.5 rounded-[14px] text-xs font-bold flex items-center gap-2 transition shadow-sm"
        >
            <i class="fa-solid fa-plus"></i>
            Tambah Lokasi
        </button>
    </div>

    <!-- TABEL -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="border-b border-border-color text-[9px] font-semibold text-muted-text uppercase bg-light-sage/30">
                    <th class="p-4">No</th>
                    <th class="p-4">Lokasi</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Koordinat</th>
                    <th class="p-4">Radius</th>
                    <th class="p-4">QR Token</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-border-color/50 text-xs text-text-main">

                @forelse($locations as $i => $location)

                    <tr class="hover:bg-light-sage/20 transition">

                        <!-- NO -->
                        <td class="p-4 font-semibold text-muted-text">
                            {{ $i + 1 }}
                        </td>

                        <!-- LOKASI -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9 rounded-[11px] bg-sage text-dark-green flex items-center justify-center">
                                    <i class="fa-solid fa-location-dot text-xs"></i>
                                </div>

                                <div>
                                    <strong class="block text-dark-green text-xs">
                                        {{ $location->nama_lokasi }}
                                    </strong>

                                    <span class="text-[9px] text-muted-text">
                                        Lokasi absensi
                                    </span>
                                </div>

                            </div>
                        </td>

                        <!-- KODE -->
                        <td class="p-4">
                            <span class="px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-semibold">
                                {{ $location->kode_lokasi }}
                            </span>
                        </td>

                        <!-- KOORDINAT -->
                        <td class="p-4">
                            <div class="text-[10px] text-text-main">
                                <div>
                                    <span class="text-muted-text">Lat:</span>
                                    {{ $location->latitude }}
                                </div>

                                <div class="mt-1">
                                    <span class="text-muted-text">Lng:</span>
                                    {{ $location->longitude }}
                                </div>
                            </div>
                        </td>

                        <!-- RADIUS -->
                        <td class="p-4">
                            <span class="font-semibold text-dark-green">
                                {{ $location->radius }} m
                            </span>
                        </td>

                        <!-- JUMLAH TOKEN -->
                        <td class="p-4">
                            <span class="px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-semibold">
                                {{ $location->qr_tokens_count }} token
                            </span>
                        </td>

                        <!-- STATUS -->
                        <td class="p-4">

                            @if($location->status)
                                <span class="px-3 py-1 bg-sage text-dark-green rounded-full text-[10px] font-bold">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-muted-text rounded-full text-[10px] font-bold">
                                    Tidak Aktif
                                </span>
                            @endif

                        </td>

                        <!-- AKSI -->
                        <td class="p-4 text-center">

                            <div class="flex items-center justify-center gap-1">

                                <button
                                    type="button"
                                    onclick="editLokasi(
                                        '{{ $location->id }}',
                                        @js($location->nama_lokasi),
                                        @js($location->kode_lokasi),
                                        @js($location->latitude),
                                        @js($location->longitude),
                                        @js($location->radius),
                                        '{{ $location->status }}'
                                    )"
                                    class="p-2 text-muted-text hover:text-olive rounded-[10px] hover:bg-light-sage transition"
                                    title="Edit"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <form
                                    action="{{ route('qr-locations.destroy', $location->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')"
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
                        <td colspan="8" class="p-10 text-center">

                            <div class="flex flex-col items-center">

                                <div class="w-12 h-12 rounded-[14px] bg-light-sage text-dark-green flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-location-dot text-lg"></i>
                                </div>

                                <strong class="text-xs text-dark-green">
                                    Belum ada lokasi QR
                                </strong>

                                <span class="text-[10px] text-muted-text mt-1">
                                    Tambahkan lokasi untuk digunakan sebagai titik absensi.
                                </span>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>


<!-- MODAL TAMBAH -->
<div
    id="modalTambahLokasi"
    class="fixed inset-0 bg-dark-green/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4"
>

    <div class="bg-white rounded-[23px] w-full max-w-lg p-6 shadow-xl relative border border-border-color max-h-[90vh] overflow-y-auto">

        <button
            type="button"
            onclick="closeModal('modalTambahLokasi')"
            class="absolute top-5 right-5 text-muted-text hover:text-dark-green"
        >
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h3 class="text-base font-bold text-dark-green mb-1">
            Tambah Lokasi QR
        </h3>

        <p class="text-[10px] text-muted-text mb-5">
            Masukkan titik lokasi yang akan digunakan untuk absensi.
        </p>

        <form
            action="{{ route('qr-locations.store') }}"
            method="POST"
            class="space-y-3"
        >
            @csrf

            <!-- NAMA -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Nama Lokasi
                </label>

                <input
                    type="text"
                    name="nama_lokasi"
                    required
                    value="{{ old('nama_lokasi') }}"
                    placeholder="Contoh: SMKN 1 Maja"
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
            </div>

            <!-- KODE -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Kode Lokasi
                </label>

                <input
                    type="text"
                    name="kode_lokasi"
                    required
                    value="{{ old('kode_lokasi') }}"
                    placeholder="Contoh: SEKOLAH-01"
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
            </div>

            <!-- KOORDINAT -->
            <div class="grid grid-cols-2 gap-3">

                <div>
                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        Latitude
                    </label>

                    <input
                        type="number"
                        step="any"
                        name="latitude"
                        required
                        value="{{ old('latitude') }}"
                        placeholder="-6.123456"
                        class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    >
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        Longitude
                    </label>

                    <input
                        type="number"
                        step="any"
                        name="longitude"
                        required
                        value="{{ old('longitude') }}"
                        placeholder="106.123456"
                        class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    >
                </div>

            </div>

            <!-- RADIUS -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Radius Absensi (meter)
                </label>

                <input
                    type="number"
                    name="radius"
                    min="1"
                    required
                    value="{{ old('radius', 100) }}"
                    placeholder="100"
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
            </div>

            <!-- STATUS -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Status
                </label>

                <select
                    name="status"
                    required
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <!-- ACTION -->
            <div class="flex justify-end gap-2 pt-3 border-t border-border-color">

                <button
                    type="button"
                    onclick="closeModal('modalTambahLokasi')"
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


<!-- MODAL EDIT -->
<div
    id="modalEditLokasi"
    class="fixed inset-0 bg-dark-green/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4"
>

    <div class="bg-white rounded-[23px] w-full max-w-lg p-6 shadow-xl relative border border-border-color max-h-[90vh] overflow-y-auto">

        <button
            type="button"
            onclick="closeModal('modalEditLokasi')"
            class="absolute top-5 right-5 text-muted-text hover:text-dark-green"
        >
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h3 class="text-base font-bold text-dark-green mb-1">
            Edit Lokasi QR
        </h3>

        <p class="text-[10px] text-muted-text mb-5">
            Perbarui informasi lokasi absensi.
        </p>

        <form
            id="formEditLokasi"
            method="POST"
            class="space-y-3"
        >
            @csrf
            @method('PUT')

            <!-- NAMA -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Nama Lokasi
                </label>

                <input
                    type="text"
                    id="edit_nama_lokasi"
                    name="nama_lokasi"
                    required
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
            </div>

            <!-- KODE -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Kode Lokasi
                </label>

                <input
                    type="text"
                    id="edit_kode_lokasi"
                    name="kode_lokasi"
                    required
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
            </div>

            <!-- KOORDINAT -->
            <div class="grid grid-cols-2 gap-3">

                <div>
                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        Latitude
                    </label>

                    <input
                        type="number"
                        step="any"
                        id="edit_latitude"
                        name="latitude"
                        required
                        class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    >
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-dark-green mb-1">
                        Longitude
                    </label>

                    <input
                        type="number"
                        step="any"
                        id="edit_longitude"
                        name="longitude"
                        required
                        class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                    >
                </div>

            </div>

            <!-- RADIUS -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Radius Absensi (meter)
                </label>

                <input
                    type="number"
                    id="edit_radius"
                    name="radius"
                    min="1"
                    required
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
            </div>

            <!-- STATUS -->
            <div>
                <label class="block text-[10px] font-bold text-dark-green mb-1">
                    Status
                </label>

                <select
                    id="edit_status"
                    name="status"
                    required
                    class="w-full px-3.5 py-2.5 bg-light-sage/30 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
                >
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <!-- ACTION -->
            <div class="flex justify-end gap-2 pt-3 border-t border-border-color">

                <button
                    type="button"
                    onclick="closeModal('modalEditLokasi')"
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

function editLokasi(id, nama, kode, latitude, longitude, radius, status) {
    document.getElementById('formEditLokasi').action = '/qr-locations/' + id;

    document.getElementById('edit_nama_lokasi').value = nama;
    document.getElementById('edit_kode_lokasi').value = kode;
    document.getElementById('edit_latitude').value = latitude;
    document.getElementById('edit_longitude').value = longitude;
    document.getElementById('edit_radius').value = radius;
    document.getElementById('edit_status').value = status;

    openModal('modalEditLokasi');
}
</script>

@endsection