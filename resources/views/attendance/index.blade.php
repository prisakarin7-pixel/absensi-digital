@extends('layout.app')

@section('title', 'Data Absensi | Absensi Digital')

@section('content')

<!-- TOPBAR -->
<div class="flex justify-between items-center mb-8">
    <div>
        <p class="text-[10px] font-bold text-olive tracking-[1.5px] uppercase mb-1">
            LAPORAN KEHADIRAN
        </p>
        <h1 class="text-3xl font-bold text-dark-green">
            Data Absensi
        </h1>
        <p class="text-xs text-muted-text mt-1">
            Kelola dan pantau riwayat kehadiran siswa.
        </p>
    </div>

    <div class="bg-light-sage px-4 py-3 rounded-[16px] flex items-center gap-3">
        <div class="w-10 h-10 rounded-[12px] bg-olive text-white flex items-center justify-center">
            <i class="fa-regular fa-calendar text-base"></i>
        </div>
        <div>
            <span class="block text-[9px] text-muted-text">
                Hari ini
            </span>
            <strong class="text-xs text-dark-green font-bold">
                {{ \Carbon\Carbon::now()->format('d M Y') }}
            </strong>
        </div>
    </div>
</div>

<!-- NOTIFIKASI -->
@if(session('success'))
<div class="mb-5 p-4 bg-sage text-dark-green rounded-[14px] font-bold text-xs flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i>
    {{ session('success') }}
</div>
@endif

<!-- RINGKASAN ABSENSI -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-[20px] border border-border-color p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[9px] font-bold text-muted-text uppercase tracking-wide">
                Hadir
            </span>
            <div class="w-9 h-9 rounded-[11px] bg-light-sage text-dark-green flex items-center justify-center">
                <i class="fa-solid fa-user-check text-sm"></i>
            </div>
        </div>

        <strong class="text-2xl font-bold text-dark-green">
            {{ $hadirToday }}
        </strong>

        <p class="text-[9px] text-muted-text mt-1">
            Data pada halaman ini
        </p>
    </div>

    <div class="bg-white rounded-[20px] border border-border-color p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[9px] font-bold text-muted-text uppercase tracking-wide">
                Izin
            </span>
            <div class="w-9 h-9 rounded-[11px] bg-light-sage text-dark-green flex items-center justify-center">
                <i class="fa-solid fa-file-circle-check text-sm"></i>
            </div>
        </div>

        <strong class="text-2xl font-bold text-dark-green">
            {{ $izinToday }}
        </strong>

        <p class="text-[9px] text-muted-text mt-1">
            Data pada halaman ini
        </p>
    </div>

    <div class="bg-white rounded-[20px] border border-border-color p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[9px] font-bold text-muted-text uppercase tracking-wide">
                Sakit
            </span>
            <div class="w-9 h-9 rounded-[11px] bg-light-sage text-dark-green flex items-center justify-center">
                <i class="fa-solid fa-notes-medical text-sm"></i>
            </div>
        </div>

        <strong class="text-2xl font-bold text-dark-green">
            {{ $sakitToday }}
        </strong>

        <p class="text-[9px] text-muted-text mt-1">
            Data pada halaman ini
        </p>
    </div>

    <div class="bg-white rounded-[20px] border border-border-color p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[9px] font-bold text-muted-text uppercase tracking-wide">
                Alpha
            </span>
            <div class="w-9 h-9 rounded-[11px] bg-light-sage text-dark-green flex items-center justify-center">
                <i class="fa-solid fa-user-xmark text-sm"></i>
            </div>
        </div>

        <strong class="text-2xl font-bold text-dark-green">
            {{ $alphaToday }}
        </strong>

        <p class="text-[9px] text-muted-text mt-1">
            Data pada halaman ini
        </p>
    </div>

</div>

<!-- FILTER & SEARCH -->
<div class="bg-white p-5 rounded-[23px] border border-border-color shadow-sm mb-6">

    <div class="flex items-center justify-between mb-4">
        <div>
            <span class="block text-[9px] font-bold text-olive tracking-[1.2px] uppercase">
                DATA ABSENSI
            </span>
            <h2 class="text-base font-bold text-dark-green">
                Riwayat Kehadiran
            </h2>
        </div>
    </div>

    <form method="GET"
          action="{{ route('attendance.index') }}"
          class="flex flex-wrap gap-3 items-center">

        <!-- SEARCH -->
        <div class="relative flex-1 min-w-[220px]">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-muted-text text-xs"></i>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau NIS..."
                class="w-full pl-10 pr-4 py-2.5 bg-light-sage/40 border border-border-color rounded-[14px] text-xs text-text-main focus:outline-none focus:border-olive"
            >
        </div>

        <!-- TANGGAL -->
        <div class="relative">
            <input
                type="date"
                name="tanggal"
                value="{{ request('tanggal') }}"
                class="bg-light-sage/40 border border-border-color rounded-[14px] px-3 py-2.5 text-xs text-text-main focus:outline-none focus:border-olive"
            >
        </div>

        <!-- STATUS -->
        <select
            name="status"
            class="bg-light-sage/40 border border-border-color rounded-[14px] px-3 py-2.5 text-xs text-text-main focus:outline-none focus:border-olive"
        >
            <option value="">Semua Status</option>

            @foreach(['hadir', 'izin', 'sakit', 'alpha'] as $status)
                <option
                    value="{{ $status }}"
                    {{ request('status') == $status ? 'selected' : '' }}
                >
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        <!-- FILTER -->
        <button
            type="submit"
            class="bg-olive hover:bg-dark-green text-white px-5 py-2.5 rounded-[14px] text-xs font-bold flex items-center gap-2 transition shadow-sm"
        >
            <i class="fa-solid fa-filter"></i>
            Filter
        </button>

        <!-- RESET -->
        @if(request()->hasAny(['search', 'tanggal', 'status']))
            <a
                href="{{ route('attendance.index') }}"
                class="bg-light-sage hover:bg-sage text-dark-green px-5 py-2.5 rounded-[14px] text-xs font-bold flex items-center gap-2 transition"
            >
                <i class="fa-solid fa-rotate-left"></i>
                Reset
            </a>
        @endif

    </form>

</div>

<!-- TABEL -->
<div class="bg-white rounded-[23px] border border-border-color shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="border-b border-border-color text-[9px] font-semibold text-muted-text uppercase bg-light-sage/30">

                    <th class="p-4">
                        No
                    </th>

                    <th class="p-4">
                        Siswa
                    </th>

                    <th class="p-4">
                        Kelas
                    </th>

                    <th class="p-4">
                        Tanggal
                    </th>

                    <th class="p-4">
                        Waktu
                    </th>

                    <th class="p-4">
                        Lokasi
                    </th>

                    <th class="p-4 text-center">
                        Status
                    </th>

                    <th class="p-4 text-center">
                        Aksi
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-border-color/50 text-xs text-text-main">

                @forelse($attendances as $i => $attendance)

                    <tr class="hover:bg-light-sage/20 transition">

                        <!-- NO -->
                        <td class="p-4 text-muted-text">
                            {{ $attendances->firstItem() + $i }}
                        </td>

                        <!-- SISWA -->
                        <td class="p-4">

                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9 rounded-[11px] bg-sage text-dark-green font-bold flex items-center justify-center text-xs uppercase">
                                    {{ strtoupper(substr($attendance->student->nama ?? '??', 0, 2)) }}
                                </div>

                                <div>
                                    <strong class="block text-dark-green text-xs">
                                        {{ $attendance->student->nama ?? '-' }}
                                    </strong>

                                    <span class="text-[9px] text-muted-text">
                                        NIS {{ $attendance->student->nis ?? '-' }}
                                    </span>
                                </div>

                            </div>

                        </td>

                        <!-- KELAS -->
                        <td class="p-4">

                            <span class="px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-semibold">
                                {{ $attendance->student->class->nama_kelas ?? 'Tanpa Kelas' }}
                            </span>

                        </td>

                        <!-- TANGGAL -->
                        <td class="p-4 whitespace-nowrap">

                            {{ \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') }}

                        </td>

                        <!-- WAKTU -->
                        <td class="p-4 whitespace-nowrap">

                            <span class="font-semibold text-dark-green">
                                {{ substr($attendance->waktu ?? '00:00', 0, 5) }}
                            </span>

                        </td>

                        <!-- LOKASI -->
                        <td class="p-4">

                            <span class="px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-semibold inline-flex items-center gap-1">

                                <i class="fa-solid fa-location-dot text-[9px]"></i>

                                {{ $attendance->qrLocation->nama_lokasi ?? '-' }}

                            </span>

                        </td>

                        <!-- STATUS -->
                        <td class="p-4 text-center">

                            @if($attendance->status === 'hadir')

                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-bold">
                                    <i class="fa-solid fa-circle-check text-[9px]"></i>
                                    Hadir
                                </span>

                            @elseif($attendance->status === 'izin')

                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-sage text-dark-green rounded-full text-[10px] font-bold">
                                    <i class="fa-solid fa-file-circle-check text-[9px]"></i>
                                    Izin
                                </span>

                            @elseif($attendance->status === 'sakit')

                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-bold">
                                    <i class="fa-solid fa-notes-medical text-[9px]"></i>
                                    Sakit
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-light-sage text-dark-green rounded-full text-[10px] font-bold">
                                    <i class="fa-solid fa-circle-xmark text-[9px]"></i>
                                    Alpha
                                </span>

                            @endif

                        </td>

                        <!-- AKSI -->
                        <td class="p-4">

                            <div class="flex items-center justify-center gap-1">

                                <a
                                    href="{{ route('attendance.show', $attendance->id) }}"
                                    title="Lihat Detail"
                                    class="p-2 text-muted-text hover:text-olive rounded-[10px] hover:bg-light-sage transition"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <form
                                    action="{{ route('attendance.destroy', $attendance->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        title="Hapus"
                                        class="p-2 text-muted-text hover:text-red-600 rounded-[10px] hover:bg-light-sage transition"
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

                            <div class="flex flex-col items-center justify-center">

                                <div class="w-12 h-12 rounded-[14px] bg-light-sage text-dark-green flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-clipboard-list text-lg"></i>
                                </div>

                                <strong class="block text-dark-green text-xs">
                                    Belum ada data absensi
                                </strong>

                                <span class="text-[9px] text-muted-text mt-1">
                                    Data akan muncul setelah siswa melakukan absensi.
                                </span>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    @if($attendances->hasPages())

        <div class="p-4 border-t border-border-color">
            {{ $attendances->links() }}
        </div>

    @endif

</div>

@endsection