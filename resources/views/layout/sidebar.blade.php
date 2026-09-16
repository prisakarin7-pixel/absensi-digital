<aside class="sidebar">

    {{-- ============================= --}}
    {{-- PROFILE ADMIN --}}
    {{-- ============================= --}}
    <div class="profile">

        <div class="profile-picture">
            <i data-lucide="user"></i>
        </div>

        <div class="profile-info">
            <h3>{{ auth()->user()->name ?? 'Admin' }}</h3>
            <p>Administrator</p>
        </div>

    </div>


    {{-- ============================= --}}
    {{-- MENU NAVIGASI --}}
    {{-- ============================= --}}
    <nav class="menu">

        {{-- DASHBOARD --}}
        <a href="{{ url('/dashboard') }}"
           class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">

            <span class="menu-icon">
                <i data-lucide="layout-dashboard"></i>
            </span>

            <span>Dashboard</span>
        </a>


        {{-- DATA SISWA --}}
        <a href="{{ url('/students') }}"
           class="menu-item {{ request()->is('students*') ? 'active' : '' }}">

            <span class="menu-icon">
                <i data-lucide="users"></i>
            </span>

            <span>Data Siswa</span>
        </a>


        {{-- DATA KELAS --}}
        <a href="{{ url('/classes') }}"
           class="menu-item {{ request()->is('classes*') ? 'active' : '' }}">

            <span class="menu-icon">
                <i data-lucide="school"></i>
            </span>

            <span>Data Kelas</span>
        </a>

        {{-- QR LOCATION --}}
        <a href="{{ url('/qr-location') }}"
           class="menu-item {{ request()->is('qr-location*') ? 'active' : '' }}">

            <span class="menu-icon">
                <i data-lucide="map-pin"></i>
            </span>

            <span>QR Location</span>
        </a>


        {{-- QR TOKEN --}}
        <a href="{{ url('/qr-token') }}"
           class="menu-item {{ request()->is('qr-token*') ? 'active' : '' }}">

            <span class="menu-icon">
                <i data-lucide="qr-code"></i>
            </span>

            <span>QR Token</span>
        </a>


        {{-- DATA ABSENSI --}}
        <a href="{{ url('/attendance') }}"
           class="menu-item {{ request()->is('attendance*') ? 'active' : '' }}">

            <span class="menu-icon">
                <i data-lucide="clipboard-check"></i>
            </span>

            <span>Data Absensi</span>
        </a>

    </nav>


    {{-- ============================= --}}
    {{-- LOGOUT --}}
    {{-- ============================= --}}
    <div class="logout-area">

        <form action="{{ url('/logout') }}" method="POST">
            @csrf

            <button type="submit" class="logout-button">

                <span class="menu-icon">
                    <i data-lucide="log-out"></i>
                </span>

                <span>Keluar</span>

            </button>
        </form>

    </div>

</aside>


{{-- ============================= --}}
{{-- LUCIDE ICON --}}
{{-- ============================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    });
</script>