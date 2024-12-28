<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Pemilik</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Register as Pemilik</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="hidden" name="role" value="PEMILIK"> <!-- Role is set here -->
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
                <input type="text" name="phone">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>

</html>
