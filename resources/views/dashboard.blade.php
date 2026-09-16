@extends('layout.app')

@section('title', 'Dashboard | Absensi Digital')

@section('content')

<main class="main-content">

    {{-- ========================================= --}}
    {{-- TOP BAR --}}
    {{-- ========================================= --}}
    <header class="topbar">

        <div>
            <p class="small-title">ADMIN DASHBOARD</p>

            <h1>
                Selamat Datang, {{ auth()->user()->name ?? 'Admin' }} 👋
            </h1>

            <p class="subtitle">
                Pantau aktivitas absensi sekolah dengan mudah.
            </p>
        </div>


        <div class="topbar-date">

            <div class="date-icon">
                <i data-lucide="calendar-days"></i>
            </div>

            <div>
                <span>Hari ini</span>

                <strong>
                    {{ $today->translatedFormat('d M Y') }}
                </strong>
            </div>

        </div>

    </header>


    {{-- ========================================= --}}
    {{-- STATISTICS --}}
    {{-- ========================================= --}}
    <section class="statistics">


        {{-- TOTAL SISWA --}}
        <div class="stat-card">

            <div class="stat-icon">
                <i data-lucide="graduation-cap"></i>
            </div>

            <div class="stat-content">

                <span>Total Siswa</span>

                <h2>
                    {{ $totalStudents }}
                </h2>

                <small>
                    Data siswa terdaftar
                </small>

            </div>

        </div>


        {{-- TOTAL KELAS --}}
        <div class="stat-card">

            <div class="stat-icon">
                <i data-lucide="school"></i>
            </div>

            <div class="stat-content">

                <span>Total Kelas</span>

                <h2>
                    {{ $totalClasses }}
                </h2>

                <small>
                    Kelas aktif
                </small>

            </div>

        </div>

        {{-- PERSENTASE ABSENSI --}}
        <div class="stat-card">

            <div class="stat-icon attendance-icon">
                <i data-lucide="clipboard-check"></i>
            </div>

            <div class="stat-content">

                <span>Absensi Hari Ini</span>

                <h2>
                    {{ $attendancePercentage }}%
                </h2>

                <small>
                    Tingkat kehadiran
                </small>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- CONTENT GRID --}}
    {{-- ========================================= --}}
    <section class="content-grid">


        {{-- ========================================= --}}
        {{-- ATTENDANCE SUMMARY --}}
        {{-- ========================================= --}}
        <div class="card attendance-card">

            <div class="card-header">

                <div>

                    <span class="card-label">
                        RINGKASAN
                    </span>

                    <h2>
                        Kehadiran Hari Ini
                    </h2>

                </div>

                <button
                    type="button"
                    class="more-button"
                    aria-label="Menu ringkasan kehadiran"
                >
                    <i data-lucide="more-horizontal"></i>
                </button>

            </div>


            <div class="attendance-layout">


                {{-- CIRCLE PERSENTASE --}}
                <div
                    class="circle-progress"
                    style="--progress: {{ $attendancePercentage }}%;"
                >

                    <div class="circle-inner">

                        <strong>
                            {{ $attendancePercentage }}%
                        </strong>

                        <span>
                            Hadir
                        </span>

                    </div>

                </div>


                {{-- DAFTAR STATUS --}}
                <div class="attendance-list">


                    {{-- HADIR --}}
                    <div class="attendance-item">

                        <span class="attendance-dot hadir"></span>

                        <div>

                            <strong>
                                Hadir
                            </strong>

                            <small>
                                Siswa hadir hari ini
                            </small>

                        </div>

                        <b>
                            {{ $hadirToday }}
                        </b>

                    </div>


                    {{-- IZIN --}}
                    <div class="attendance-item">

                        <span class="attendance-dot izin"></span>

                        <div>

                            <strong>
                                Izin
                            </strong>

                            <small>
                                Siswa dengan izin
                            </small>

                        </div>

                        <b>
                            {{ $izinToday }}
                        </b>

                    </div>


                    {{-- SAKIT --}}
                    <div class="attendance-item">

                        <span class="attendance-dot sakit"></span>

                        <div>

                            <strong>
                                Sakit
                            </strong>

                            <small>
                                Siswa tidak masuk
                            </small>

                        </div>

                        <b>
                            {{ $sakitToday }}
                        </b>

                    </div>


                    {{-- ALPHA --}}
                    <div class="attendance-item">

                        <span class="attendance-dot alpha"></span>

                        <div>

                            <strong>
                                Alpha
                            </strong>

                            <small>
                                Tanpa keterangan
                            </small>

                        </div>

                        <b>
                            {{ $alphaToday }}
                        </b>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================= --}}
        {{-- QUICK ACTION --}}
        {{-- ========================================= --}}
        <div class="card quick-card">

            <div class="card-header">

                <div>

                    <span class="card-label">
                        AKSES CEPAT
                    </span>

                    <h2>
                        Menu Utama
                    </h2>

                </div>

            </div>


            <div class="quick-menu">


                {{-- DATA SISWA --}}
                <a
                    href="{{ url('/students') }}"
                    class="quick-item"
                >

                    <div class="quick-icon">
                        <i data-lucide="user-plus"></i>
                    </div>

                    <div>

                        <strong>
                            Data Siswa
                        </strong>

                        <span>
                            Kelola siswa
                        </span>

                    </div>

                    <i data-lucide="chevron-right"></i>

                </a>


                {{-- DATA KELAS --}}
                <a
                    href="{{ url('/classes') }}"
                    class="quick-item"
                >

                    <div class="quick-icon">
                        <i data-lucide="school"></i>
                    </div>

                    <div>

                        <strong>
                            Data Kelas
                        </strong>

                        <span>
                            Kelola kelas
                        </span>

                    </div>

                    <i data-lucide="chevron-right"></i>

                </a>


                {{-- DATA ABSENSI --}}
                <a
                    href="{{ url('/attendance') }}"
                    class="quick-item"
                >

                    <div class="quick-icon">
                        <i data-lucide="clipboard-check"></i>
                    </div>

                    <div>

                        <strong>
                            Data Absensi
                        </strong>

                        <span>
                            Lihat absensi
                        </span>

                    </div>

                    <i data-lucide="chevron-right"></i>

                </a>

            </div>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- RECENT ATTENDANCE --}}
    {{-- ========================================= --}}
    <section class="card recent-card">

        <div class="card-header">

            <div>

                <span class="card-label">
                    AKTIVITAS
                </span>

                <h2>
                    Absensi Terbaru
                </h2>

            </div>

            <a
                href="{{ url('/attendance') }}"
                class="view-all"
            >
                Lihat semua

                <i data-lucide="arrow-up-right"></i>
            </a>

        </div>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            Siswa
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Waktu
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($recentAttendances as $attendance)

                        @php
                            $nama = $attendance->nama_siswa ?? 'Siswa';

                            $namaParts = preg_split('/\s+/', trim($nama));

                            $initials = '';

                            foreach (array_slice($namaParts, 0, 2) as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }

                            $status = strtolower(trim($attendance->status ?? ''));

                            $statusLabel = ucfirst($status);

                            $statusClass = match ($status) {
                                'hadir' => 'hadir-status',
                                'izin' => 'izin-status',
                                'sakit' => 'sakit-status',
                                'alpha' => 'alpha-status',
                                default => 'izin-status',
                            };

                            $waktu = $attendance->waktu
                                ? substr((string) $attendance->waktu, 0, 5)
                                : '-';
                        @endphp


                        <tr>

                            <td>

                                <div class="student">

                                    <div class="student-avatar">
                                        {{ $initials ?: 'S' }}
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $nama }}
                                        </strong>

                                        <span>
                                            {{ $attendance->nis ?? '-' }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            <td>
                                {{ $attendance->nama_kelas ?? '-' }}
                            </td>


                            <td>
                                {{ $waktu }} WIB
                            </td>


                            <td>

                                <span class="status {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                style="text-align: center; padding: 30px;"
                            >

                                Belum ada data absensi terbaru.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>


    {{-- ========================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================= --}}
    <footer>

        © {{ date('Y') }} Absensi Digital • Admin Panel

    </footer>

</main>


@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    });
</script>

@endpush

@endsection