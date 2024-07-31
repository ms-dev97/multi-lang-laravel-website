<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="{{ route('loginPost') }}" method="POST">
        @csrf
        <input type="email" name="email">
        @error('email')
            {{ $message }}
        @enderror
        <input type="password" name="password">
        <button type="submit">Login</button>
    </form>
</body>
</html>