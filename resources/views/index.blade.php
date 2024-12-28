<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Penyewa or Pemilik</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Daftar sebagai:</h2>
        <div>
            <a href="{{ route('register.penyewa') }}" class="btn btn-primary">Penyewa</a>
            <a href="{{ route('register.pemilik') }}" class="btn btn-secondary">Pemilik</a>
        </div>
    </div>
</body>

</html>
