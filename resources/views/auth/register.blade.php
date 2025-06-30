<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - TOVA Point of Sale</title>

    <link href="https://fonts.googleapis.com/css2?family=Another+Shabby&family=Nunito:wght@300;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        @keyframes gradientBG {
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
            background: linear-gradient(-45deg, #800000, #a52a2a, #6e0101, #b22222);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 2.5rem 3rem;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        .login-card h1 { margin: 0; }
        
        /* [BARU] Styling untuk judul utama */
        .brand-main {
            font-family: 'Another Shabby', cursive; /* Menggunakan font dari Google Fonts */
            font-style: italic;
            font-size: 4rem;
            line-height: 1;
            color: #6a040f;
            display: block;
        }
        
        .brand-subtitle {
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: #495057;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-top: 8px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1.5rem;
            color: #4c0000;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.2);
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
        }
        
        .btn-primary {
            background-color: #8B0000;
            border-color: #8B0000;
            border-radius: 8px;
            padding: 0.8rem;
            font-weight: 700;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #a52a2a;
            border-color: #a52a2a;
            transform: translateY(-2px);
        }

        .small-link {
            font-size: 0.875rem;
            display: block;
            margin-top: 1.5rem;
            color: #8B0000;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h1>
            <span class="brand-main">TOVA</span>
            <span class="brand-subtitle">Point of Sale</span>
        </h1>
        <h2 class="page-title">Registrasi Akun</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                @error('name')
                    <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </form>

        <a class="small-link" href="{{ route('login') }}">
            Sudah punya akun? Masuk di sini
        </a>
    </div>

</body>
</html>