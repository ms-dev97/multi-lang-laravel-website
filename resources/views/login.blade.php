<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/admin/css/main.css') }}" rel="stylesheet">
    <title>تسجيل الدخول</title>
    <style>
        .form-wrapper {
            width: 100%;
            min-height: 100vh;
            padding: 0 20px;
            display: grid;
            place-items: center;
        }
        .login-form {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        .greeting {
            margin-bottom: 1rem;
        }
        .greeting .welcome {
            font-weight: 500;
            font-size: 1.25rem;
        }
        .greeting .welcome + span,
        #rememberme + label {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="form-wrapper">
        <form action="{{ route('loginPost') }}" method="POST" class="login-form">
            @csrf

            <div class="greeting">
                <div class="welcome">مرحباً بعودتك!</div>
                <span>قم بتسجيل الدخول لتتمكن من المتابعة</span>
            </div>

            <div class="form-group">
                <label for="email">البريد الالكتروني</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')
                    <div class="input-invalid">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group flex align-items-center g-0.5rem">
                <input type="checkbox" name="rememberme" id="rememberme" class="form-control">
                <label for="rememberme">تذكرني</label>
            </div>

            @if ($errors->any())
                <ul class="form-errors">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            
            <div class="form-group">
                <button type="submit" class="btn btn-fill btn-primary">تسجيل الدخول</button>
            </div>
        </form>
    </div>
</body>
</html>