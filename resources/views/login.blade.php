<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Absensi Digital</title>

    @vite(['resources/css/absensi.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <script src="https://unpkg.com/lucide@latest"></script>

</head>


<body>


<div class="login-page">


    <div class="login-container">


        <!-- LEFT -->

        <div class="login-visual">

            <div class="visual-content">

                <div class="visual-logo">
                    <i data-lucide="scan-face"></i>
                </div>

                <h1>
                    Absensi Digital
                </h1>

                <p>
                    Sistem informasi absensi sekolah yang
                    sederhana, modern, dan mudah digunakan
                    untuk mengelola kehadiran siswa.
                </p>

            </div>

        </div>



        <!-- RIGHT -->

        <div class="login-form-area">


            <div class="login-brand">

                <div class="brand-icon">
                    <i data-lucide="qr-code"></i>
                </div>

                <h2>
                    Welcome Back
                </h2>

                <p>
                    Masuk ke dashboard Absensi Digital
                </p>

            </div>



            <form
                class="login-form"
                method="POST"
                action="/login"
            >

                @csrf


                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <div class="input-wrapper">

                        <i data-lucide="mail"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Masukkan email"
                            required
                        >

                    </div>

                </div>



                <!-- PASSWORD -->

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-wrapper">

                        <i data-lucide="lock-keyhole"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                        >

                    </div>

                </div>



                <!-- BUTTON -->

                <button
                    type="submit"
                    class="login-button"
                >
                    Login
                </button>


            </form>


            <div class="login-footer">

                © {{ date('Y') }} Absensi Digital

            </div>


        </div>


    </div>


</div>


<script>
    lucide.createIcons();
</script>


</body>

</html>