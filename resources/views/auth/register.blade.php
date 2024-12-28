<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" required>
            </div>
            <div>
                <label for="email">Email (optional)</label>
                <input type="email" name="email">
            </div>
            <div>
                <label for="phone">Phone (optional)</label>
                <input type="number" name="phone">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label for="role">Register As:</label>
                <select name="role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name_roles }}">{{ $role->name_roles }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>

</html>
