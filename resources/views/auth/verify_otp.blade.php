<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <form method="POST" action="{{ route('verifyOtp', ['id' => $userId]) }}">
            @csrf
            <div>
                <label for="otp">OTP Code</label>
                <input type="text" name="otp" required>
            </div>
            <button type="submit">Verify</button>
        </form>
        <br>
        <form method="POST" action="{{ route('resendOtp', ['id' => $userId]) }}">
            @csrf
            <button type="submit">Did not receive OTP?</button>
            <
