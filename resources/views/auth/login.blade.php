<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-t">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TOVA Point of Sale - Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Another+Shabby&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --maroon-deep: #6a040f;
            --maroon-bright: #9d0208;
            --maroon-dark-bg: #3d0000;
            --off-white: #f8f9fa;
            --grey-text: #495057;
            --light-grey-border: #ced4da;
        }
        
        @keyframes animateGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--grey-text);
            background: linear-gradient(135deg, var(--maroon-dark-bg), var(--maroon-deep), var(--maroon-bright));
            background-size: 400% 400%;
            animation: animateGradient 15s ease-in-out infinite;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem 3rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        
        .login-card h1 {
            margin-bottom: 2rem;
        }

        .brand-main {
            font-family: 'Another Shabby', cursive;
            font-style: italic;
            font-size: 4rem;
            line-height: 1;
            color: var(--maroon-deep);
            display: block;
        }
        
        .brand-subtitle {
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--grey-text);
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-top: 8px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.25rem;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid var(--light-grey-border);
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--maroon-deep);
            box-shadow: 0 0 0 3px rgba(106, 4, 15, 0.2);
        }

        .btn-login {
            background-color: var(--maroon-deep);
            border: none;
            border-radius: 8px;
            padding: 0.8rem;
            font-weight: 700;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-login:hover {
            background-color: var(--maroon-bright);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h1>
            <span class="brand-main">TOVA</span>
            <span class="brand-subtitle">Point of Sale</span>
        </h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus placeholder="email@example.com">
                @error('email')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required placeholder="Masukkan kata sandi">
                @error('password')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-check mb-4 text-left">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label ml-2" for="remember">Ingat Saya</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login">Masuk</button>
        </form>
        @if (Route::has('register'))
            <a class="small-link mt-4 d-block" href="{{ route('register') }}">
                Belum punya akun? Daftar di sini
            </a>
        @endif
    </div>

</body>
</html>