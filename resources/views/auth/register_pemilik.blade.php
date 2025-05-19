<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lengkapi Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ asset('assets/img/logo.png') }}" alt="">
    </div>
    <section class="fill-data">
        <div class="form-container ">
            <div class="form-title">Lengkapi Data Pemilik Lapangan</div>
            <div class="form-subtitle">Lengkapi data diri untuk untuk melanjutkan</div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role" value="PEMILIK">
                <div class="form-group">
                    <input type="text" id="nama" name="name" class="form-control" placeholder=" " required>
                    <label for="name" class="form-label">Nama Lengkap</label>
                </div>

                <div class="form-group">
                    <input type="text" id="telepon" name="phone" class="form-control" placeholder=" " required>
                    <label for="phone" class="form-label">Nomor HP</label>
                </div>

                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control" placeholder=" ">
                    <label for="email" class="form-label">Masukkan Email</label>
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder=" " required>
                    <label for="password" class="form-label">Masukkan Kata Sandi</label>
                </div>

                <button type="submit" class="btn btn-primary">Selanjutnya</button>
            </form>
            <!-- <div>
                <a href="{{ route('login.google', 'PENYEWA') }}" class="btn btn-google">
                    <img src="https://img.icons8.com/color/16/000000/google-logo.png"> Login with Google
                </a>
                <a href="{{ route('login.facebook', 'PENYEWA') }}" class="btn btn-facebook">
                    <img src="https://img.icons8.com/fluent/16/000000/facebook-new.png"> Login with Facebook
                </a>
            </div> -->
        </div>
    </section>


</body>

</html>
