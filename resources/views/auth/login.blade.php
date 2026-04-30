<x-guest-layout>
<style>
    .lf-welcome {
        font-size: 11px; font-weight: 600;
        color: #1e40af; letter-spacing: 0.08em;
        text-transform: uppercase; margin-bottom: 0.3rem;
    }
    .lf-title {
        font-family: 'DM Serif Display', serif;
        font-size: 28px; color: #0b1d3e;
        font-weight: 400; line-height: 1.2; margin-bottom: 0.35rem;
    }
    .lf-sub {
        font-size: 12.5px; font-weight: 300;
        color: #94a3b8; margin-bottom: 2rem;
    }

    .lf-error {
        background: #fef2f2; border: 1px solid #fecaca;
        border-radius: 10px; padding: 0.75rem 1rem;
        margin-bottom: 1.25rem; font-size: 12px; color: #ef4444;
    }
    .lf-error ul { padding-left: 1rem; }

    .lf-group { margin-bottom: 1.1rem; }
    .lf-label {
        display: block; font-size: 11.5px;
        font-weight: 600; color: #475569; margin-bottom: 0.45rem;
    }
    .lf-wrap { position: relative; }

    .lf-icon {
        position: absolute; left: 13px; top: 50%;
        transform: translateY(-50%);
        color: #c4d0de; font-size: 12px;
        pointer-events: none; transition: color 0.2s;
    }
    .lf-wrap:focus-within .lf-icon { color: #1e40af; }

    .lf-input {
        width: 100%;
        padding: 0.78rem 1rem 0.78rem 2.6rem;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px; color: #334155;
        font-family: 'DM Sans', sans-serif;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .lf-input::placeholder { color: #c8d3e0; }
    .lf-input:focus {
        background: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.10);
    }

    .lf-eye {
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%);
        background: none; border: none; cursor: pointer;
        color: #b0bec9; font-size: 12px;
        transition: color 0.2s; padding: 0;
    }
    .lf-eye:hover { color: #1e40af; }

    .lf-row {
        display: flex; align-items: center;
        justify-content: space-between;
        margin: 0.9rem 0 1.5rem;
    }
    .lf-remember {
        display: flex; align-items: center; gap: 0.45rem;
        font-size: 12px; color: #64748b; font-weight: 300;
        cursor: pointer; user-select: none;
    }
    .lf-remember input { accent-color: #1e40af; width: 13px; height: 13px; cursor: pointer; }
    .lf-forgot {
        font-size: 12px; font-weight: 600;
        color: #1e40af; text-decoration: none;
        transition: opacity 0.15s;
    }
    .lf-forgot:hover { opacity: 0.7; }

    .lf-btn {
        width: 100%;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        background: #1e40af; color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 13.5px; font-weight: 700; letter-spacing: 0.05em;
        border: none; border-radius: 10px;
        padding: 0.9rem 1.5rem; cursor: pointer;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 18px rgba(30,64,175,0.35);
    }
    .lf-btn:hover {
        background: #1d3d9f;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30,64,175,0.45);
    }
    .lf-btn:active { transform: translateY(0); }
    .lf-btn .arrow { font-size: 11px; transition: transform 0.2s; }
    .lf-btn:hover .arrow { transform: translateX(3px); }

    .lf-footer {
        margin-top: 1.5rem; text-align: center;
        font-size: 10.5px; font-weight: 300; color: #c0ccd8;
    }
</style>

<p class="lf-welcome">Selamat datang kembali</p>
<h2 class="lf-title">Masuk ke PADUKA</h2>
<p class="lf-sub">Gunakan akun Anda untuk melanjutkan.</p>

@if ($errors->any())
<div class="lf-error">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('status'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:12px;color:#16a34a;">
    {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="lf-group">
        <label class="lf-label">Username atau NIP</label>
        <div class="lf-wrap">
            <i class="fas fa-user lf-icon"></i>
            <input type="text" name="login"
                   value="{{ old('login') }}"
                   required autofocus autocomplete="username"
                   placeholder="Masukkan username atau NIP"
                   class="lf-input">
        </div>
        <x-input-error :messages="$errors->get('login')" class="mt-1.5" />
    </div>

    <div class="lf-group">
        <label class="lf-label">Password</label>
        <div class="lf-wrap">
            <i class="fas fa-lock lf-icon"></i>
            <input id="pwInput" type="password" name="password"
                   required autocomplete="current-password"
                   placeholder="••••••••"
                   class="lf-input">
            <button type="button" class="lf-eye" onclick="togglePw()">
                <i id="eyeIcon" class="fas fa-eye"></i>
            </button>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
    </div>

    <div class="lf-row">
        <label class="lf-remember">
            <input type="checkbox" name="remember" id="remember_me">
            <span>Ingat saya</span>
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="lf-forgot">Lupa Password?</a>
        @endif
    </div>

    <button type="submit" class="lf-btn" id="submitBtn">
        <span>Masuk</span>
        <i class="fa-solid fa-arrow-right arrow"></i>
    </button>
</form>

<p class="lf-footer">© {{ date('Y') }} PT. Rekaindo Global Jasa · All rights reserved</p>

<script>
function togglePw() {
    const inp  = document.getElementById('pwInput');
    const icon = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        inp.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</x-guest-layout>
