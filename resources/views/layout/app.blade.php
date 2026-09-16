<!-- Google Fonts: Manrope -->
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'dark-green': '#485935',
                    'deep-green': '#3f522f',
                    'olive': '#93a267',
                    'sage': '#cadbb7',
                    'light-sage': '#e8f0df',
                    'white-bg': '#fbfbfb',
                    'text-main': '#26301f',
                    'muted-text': '#899080',
                    'border-color': '#e3e8dc',
                },
                fontFamily: {
                    sans: ['Manrope', 'sans-serif'],
                }
            }
        }
    }
</script>
    <!-- ========================================= -->
    <!-- LUCIDE ICONS -->
    <!-- ========================================= -->

    <script src="https://unpkg.com/lucide@latest"></script>


    <!-- ========================================= -->
    <!-- CSS HALAMAN -->
    <!-- ========================================= -->

    @vite(['resources/css/app.css', 'resources/css/absensi.css'])

    @stack('styles')

</head>


<body class="font-sans bg-white-bg text-text-main">


    <!-- ========================================= -->
    <!-- MAIN DASHBOARD WRAPPER -->
    <!-- ========================================= -->

    <div class="dashboard">


        <!-- ===================================== -->
        <!-- SIDEBAR -->
        <!-- ===================================== -->

        @include('layout.sidebar')


        <!-- ===================================== -->
        <!-- CONTENT -->
        <!-- ===================================== -->

        <main class="flex-1 min-w-0">

            @yield('content')

        </main>


    </div>


    <!-- ========================================= -->
    <!-- JAVASCRIPT TAMBAHAN PER HALAMAN -->
    <!-- ========================================= -->

    @stack('scripts')


</body>

</html>