<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Penyewa</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<style>
    .btn-google {
        background-color: #db4437;
        /* Google brand color */
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        text-align: center;
        display: inline-block;
        font-size: 16px;
    }

    .btn-google img {
        margin-right: 10px;
        vertical-align: middle;
    }

    .btn-facebook {
        background-color: #3b5998;
        /* Facebook brand color */
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        text-align: center;
        display: inline-block;
        font-size: 16px;
    }

    .btn-facebook img {
        margin-right: 10px;
        vertical-align: middle;
    }
</style>

<body>
    <div class="container">
        <h2>Register as Penyewa</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="hidden" name="role" value="PENYEWA">
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email">
            </div>
            <div>
                <label for="phone">Phone</label>
                <input type="number" name="phone">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <div>
            <a href="{{ route('login.google', 'PENYEWA') }}" class="btn btn-google">
                <img src="https://img.icons8.com/color/16/000000/google-logo.png"> Login with Google
            </a>
            <a href="{{ route('login.facebook', 'PENYEWA') }}" class="btn btn-facebook">
                <img src="https://img.icons8.com/fluent/16/000000/facebook-new.png"> Login with Facebook
            </a>
        </div>
    </div>
</body>

</html>
