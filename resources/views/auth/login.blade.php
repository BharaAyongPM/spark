<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
    </div>

    <section class="fill-data">
        <div class="form-container">
            <div class="form-title">Masuk ke Akun</div>
            <div class="form-subtitle">Silakan login untuk melanjutkan</div>

            @if (session('message'))
                <div class="alert alert-warning mt-2">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <input type="text" id="email_or_phone" name="email_or_phone" class="form-control" placeholder=" "
                        required>
                    <label for="email_or_phone" class="form-label">Email atau No HP</label>
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder=" " required>
                    <label for="password" class="form-label">Masukkan Kata Sandi</label>
                </div>

                <button type="submit" class="btn btn-primary">Masuk</button>
            </form>

            <div class="mt-3">
                <a href="{{ route('login.google', 'PENYEWA') }}" class="btn btn-google w-100 mb-2">
                    <img src="https://img.icons8.com/color/16/000000/google-logo.png"> Login dengan Google
                </a>
                <a href="{{ route('login.facebook', 'PENYEWA') }}" class="btn btn-facebook w-100">
                    <img src="https://img.icons8.com/fluent/16/000000/facebook-new.png"> Login dengan Facebook
                </a>
            </div>
        </div>
    </section>
</body>

</html>
