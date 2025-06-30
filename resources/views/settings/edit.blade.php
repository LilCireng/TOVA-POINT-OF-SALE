@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')

{{-- ✨ Style dipindahkan ke sini untuk memastikan berhasil dimuat ✨ --}}
<style>
    .settings-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .card-body {
        padding: 32px;
    }
    .card-footer {
        background-color: #f9fafb;
        padding: 16px 32px;
        text-align: right;
        border-top: 1px solid #e9ecef;
    }
    .profile-header {
        text-align: center;
        margin-bottom: 32px;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 16px;
        border: 4px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .profile-name {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
    }
    .profile-email {
        margin: 4px 0 0;
        font-size: 14px;
        color: #6c757d;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
    }
    .form-control {
        box-sizing: border-box;
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 16px;
    }
    .form-control[type="file"] {
        padding: 8px 12px;
    }
    .form-divider {
        margin: 32px 0;
        border: 0;
        border-top: 1px solid #e9ecef;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 4px 0;
    }
    .section-subtitle {
        font-size: 14px;
        color: #6c757d;
        margin: 0 0 24px 0;
    }
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
    }
    .btn-primary {
        background-color: #3498db;
        color: white;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="settings-container">
    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="profile-header">
                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=34495e&color=fff' }}"
                         alt="Foto Profil" class="profile-avatar" id="avatar-preview">
                    <h4 class="profile-name">{{ Auth::user()->name }}</h4>
                    <p class="profile-email">{{ Auth::user()->email }}</p>
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label for="profile_photo">Foto Profil</label>
                        <input type="file" name="profile_photo" id="profile_photo" class="form-control" onchange="previewImage(event)">
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                </div>
                
                <hr class="form-divider">

                <div class="form-section">
                    <h5 class="section-title">Ubah Password</h5>
                    <p class="section-subtitle">Biarkan kosong jika Anda tidak ingin mengubahnya.</p>
                    
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
