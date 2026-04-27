@extends('layouts.survey')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DM Sans', sans-serif;
        color: #1C1C1C;
        -webkit-font-smoothing: antialiased;
    }

    .success-wrap {
        width: 100%;
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
    }

    /* ── Icon circle ── */
    .success-icon-wrap {
        position: relative;
        width: 88px;
        height: 88px;
        margin: 0 auto 2rem;
    }
    .success-icon-ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1.5px solid rgba(74, 63, 212, 0.2);
        animation: ring-pulse 2.4s ease-in-out infinite;
    }
    .success-icon-ring:nth-child(2) {
        inset: -12px;
        border-color: rgba(74, 63, 212, 0.1);
        animation-delay: 0.4s;
    }
    @keyframes ring-pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%       { transform: scale(1.06); opacity: 0.5; }
    }
    .success-icon {
        position: relative;
        z-index: 1;
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: #1E1A5E;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .success-icon svg {
        width: 36px;
        height: 36px;
        stroke: #fff;
        stroke-width: 2.5;
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: none;
    }
    .checkmark {
        stroke-dasharray: 40;
        stroke-dashoffset: 40;
        animation: draw-check 0.5s ease forwards 0.2s;
    }
    @keyframes draw-check {
        to { stroke-dashoffset: 0; }
    }

    /* ── Text ── */
    .success-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ECEAFC;
        border-radius: 100px;
        padding: 4px 14px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #4A3FD4;
        margin-bottom: 1rem;
    }
    .success-eyebrow span {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #4A3FD4;
        display: inline-block;
    }
    .success-title {
        font-family: 'DM Serif Display', serif;
        font-size: 30px;
        font-weight: 400;
        color: #1E1A5E;
        line-height: 1.2;
        margin-bottom: 0.9rem;
        letter-spacing: -0.3px;
    }
    .success-desc {
        font-size: 14.5px;
        color: #6E6B66;
        line-height: 1.75;
        max-width: 420px;
        margin: 0 auto 2.5rem;
    }

    /* ── Card info ── */
    .success-card {
        background: #fff;
        border: 0.5px solid #E3E0D9;
        border-radius: 18px;
        padding: 1.5rem 2rem;
        margin-bottom: 2.5rem;
        width: 100%;
        max-width: 420px;
        text-align: left;
    }
    .info-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.65rem 0;
        border-bottom: 0.5px solid #F0EDE7;
        font-size: 13.5px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #4A3FD4;
        flex-shrink: 0;
    }
    .info-row span { color: #5A5754; }

    /* ── Button ── */
    .btn-again {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #1E1A5E;
        color: #fff;
        text-decoration: none;
        padding: 13px 32px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        letter-spacing: 0.01em;
        transition: background 0.15s, transform 0.1s;
    }
    .btn-again:hover  { background: #2D2880; }
    .btn-again:active { transform: scale(0.98); }
    .btn-again svg {
        width: 16px; height: 16px;
        stroke: #fff; stroke-width: 2;
        stroke-linecap: round; stroke-linejoin: round;
        fill: none;
        flex-shrink: 0;
    }
</style>

<div class="success-wrap">

    {{-- Animated check icon --}}
    <div class="success-icon-wrap">
        <div class="success-icon-ring"></div>
        <div class="success-icon-ring"></div>
        <div class="success-icon">
            <svg viewBox="0 0 24 24">
                <polyline class="checkmark" points="4,12 9,17 20,6"/>
            </svg>
        </div>
    </div>

    {{-- Eyebrow --}}
    <div class="success-eyebrow"><span></span> Survey Terkirim</div>

    {{-- Title & desc --}}
    <h1 class="success-title">Terima kasih atas penilaian Anda!</h1>
    <p class="success-desc">
        Respon Anda telah berhasil kami terima dan akan digunakan untuk meningkatkan
        kualitas pelayanan PT. Rekaindo Global Jasa.
    </p>

    {{-- Info card --}}
    <div class="success-card">
        <div class="info-row">
            <span class="dot"></span>
            <span>Data Anda telah tersimpan dengan aman</span>
        </div>
        <div class="info-row">
            <span class="dot"></span>
            <span>Tim kami akan meninjau masukan Anda</span>
        </div>
        <div class="info-row">
            <span class="dot"></span>
            <span>Terima kasih telah membantu kami berkembang</span>
        </div>
    </div>

    {{-- CTA --}}
    <a href="{{ route('feedback.form') }}" class="btn-again">
        <svg viewBox="0 0 24 24">
            <polyline points="1,4 1,10 7,10"/>
            <path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
        </svg>
        Isi Survey Lain
    </a>

</div>
@endsection
