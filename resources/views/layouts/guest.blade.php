<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('img/logo-paduka.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Animations not available in Tailwind arbitrary */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to   { opacity: 1; }
            }
            @keyframes slideRight {
                from { opacity: 0; transform: translateX(-16px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            @keyframes floatOrb {
                0%, 100% { transform: translate(0, 0) scale(1); }
                50%       { transform: translate(28px, 18px) scale(1.1); }
            }
            @keyframes floatOrb2 {
                0%, 100% { transform: translate(0, 0) scale(1); }
                50%       { transform: translate(-22px, 20px) scale(1.08); }
            }
            @keyframes floatOrb3 {
                0%   { transform: translate(0, 0) scale(1); }
                33%  { transform: translate(14px, -16px) scale(1.06); }
                66%  { transform: translate(-12px, 12px) scale(0.95); }
                100% { transform: translate(0, 0) scale(1); }
            }
            @keyframes gridScroll {
                from { background-position: 0 0; }
                to   { background-position: 28px 28px; }
            }
            @keyframes pillIconPop {
                0%   { transform: scale(1) rotate(0deg); }
                40%  { transform: scale(1.3) rotate(-8deg); }
                100% { transform: scale(1) rotate(0deg); }
            }
            @keyframes borderPulse {
                0%, 100% { opacity: 0.5; }
                50%       { opacity: 1; }
            }
            .anim-fade-up   { animation: fadeUp 0.65s cubic-bezier(0.16,1,0.3,1) both; }
            .anim-fade-in   { animation: fadeIn 0.5s ease both; }
            .anim-slide-r   { animation: slideRight 0.55s cubic-bezier(0.16,1,0.3,1) both; }
            .delay-100 { animation-delay: 0.10s; }
            .delay-150 { animation-delay: 0.15s; }
            .delay-200 { animation-delay: 0.20s; }
            .delay-250 { animation-delay: 0.25s; }
            .delay-300 { animation-delay: 0.30s; }
            .delay-400 { animation-delay: 0.40s; }
            .delay-500 { animation-delay: 0.50s; }

            .orb-float      { animation: floatOrb  11s ease-in-out infinite; }
            .orb-float-slow { animation: floatOrb2 16s ease-in-out infinite; }
            .orb-float-mid  { animation: floatOrb3 20s ease-in-out infinite; }
            .grid-scroll    { animation: gridScroll 8s linear infinite; }

            /* Input focus ring override */
            .auth-input:focus {
                outline: none;
                border-color: #0b1d3e !important;
                box-shadow: 0 0 0 3px rgba(11,29,62,0.1) !important;
            }
            .auth-input { transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; }

            /* Pill hover effects */
            .brand-pill { transition: all 0.25s cubic-bezier(0.16,1,0.3,1); }
            .brand-pill:hover {
                background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                border-color: #bfdbfe;
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(30,64,175,0.12);
            }
            .brand-pill:hover .pill-icon {
                animation: pillIconPop 0.4s ease;
                color: #1e40af;
            }
            .brand-pill .pill-icon { transition: color 0.2s; }
        </style>
    </head>
    <body class="font-['DM_Sans'] antialiased bg-white text-slate-800 min-h-screen overflow-x-hidden">

        {{-- ═══ BACKGROUND ═══ --}}
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            {{-- Base gradient --}}
            <div class="absolute inset-0 bg-gradient-to-br from-white via-blue-50/40 to-slate-100"></div>

            {{-- Animated dot grid --}}
            <div class="grid-scroll absolute inset-0 opacity-100"
                 style="background-image: radial-gradient(circle, rgba(11,29,62,0.12) 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>

            {{-- Orb 1: top-left navy - strong --}}
            <div class="orb-float absolute -top-40 -left-40 w-[600px] h-[600px] rounded-full"
                 style="background: radial-gradient(circle at 40% 40%, rgba(11,29,62,0.22), rgba(30,64,175,0.1) 50%, transparent 75%); filter: blur(50px);"></div>

            {{-- Orb 2: bottom-right blue --}}
            <div class="orb-float-slow absolute -bottom-32 -right-32 w-[500px] h-[500px] rounded-full"
                 style="background: radial-gradient(circle at 60% 60%, rgba(30,64,175,0.18), rgba(59,130,246,0.08) 50%, transparent 75%); filter: blur(55px);"></div>

            {{-- Orb 3: center-right accent --}}
            <div class="orb-float-mid absolute top-1/2 -right-20 w-[320px] h-[320px] rounded-full -translate-y-1/2"
                 style="background: radial-gradient(circle, rgba(96,165,250,0.14), transparent 70%); filter: blur(45px);"></div>

            {{-- Orb 4: bottom-left soft --}}
            <div class="orb-float absolute bottom-10 left-1/4 w-[260px] h-[260px] rounded-full"
                 style="background: radial-gradient(circle, rgba(11,29,62,0.09), transparent 70%); filter: blur(40px); animation-delay: -5s;"></div>

            {{-- Top edge line --}}
            <div class="absolute top-0 left-0 right-0 h-px"
                 style="background: linear-gradient(to right, transparent 0%, rgba(30,64,175,0.25) 30%, rgba(30,64,175,0.25) 70%, transparent 100%);"></div>

            {{-- Vertical split line (only visible on lg) --}}
            <div class="absolute top-8 bottom-8 left-1/2 w-px hidden lg:block"
                 style="background: linear-gradient(to bottom, transparent, rgba(11,29,62,0.08) 20%, rgba(11,29,62,0.08) 80%, transparent); transform: translateX(-50%);"></div>
        </div>

        {{-- ═══ PAGE GRID ═══ --}}
        <div class="relative z-10 min-h-screen grid grid-cols-1 lg:grid-cols-2">

            {{-- ─── Left: Branding ─── --}}
            <div class="hidden lg:flex flex-col justify-between px-16 py-14 border-r border-slate-100">

                <div>
                    {{-- Top badge --}}
                    <div class="anim-fade-in inline-flex items-center gap-2 border border-[#0b1d3e]/12 bg-[#0b1d3e]/[0.04] rounded-full px-3.5 py-1.5 mb-12">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.6)] animate-pulse"></span>
                        <span class="text-[10.5px] font-semibold tracking-[0.1em] uppercase text-[#0b1d3e]/60">Platform Manajemen NCR Terpusat</span>
                    </div>

                    {{-- Wordmark --}}
                    <h1 class="anim-slide-r delay-100 font-['DM_Serif_Display'] text-[80px] leading-none tracking-tight text-[#0b1d3e]">
                        PAD<span class="relative">
                            <span class="relative z-10 text-[#1e40af]">U</span>
                            <span class="absolute inset-x-0 bottom-1 h-2 bg-blue-100 -z-0 rounded"></span>
                        </span>KA
                    </h1>

                    <p class="anim-fade-up delay-200 mt-4 text-[15px] font-light text-slate-500 leading-relaxed">
                        Sistem NCR Online Terpusat<br>PT. Rekaindo Global Jasa
                    </p>

                    <p class="anim-fade-up delay-250 mt-5 text-[13.5px] font-light text-slate-400 leading-[1.9] max-w-sm">
                        Kelola, tindak lanjuti, verifikasi, dan pantau status NCR dalam satu sistem
                        yang rapi, terstruktur, dan mudah digunakan oleh seluruh unit terkait.
                    </p>

                    {{-- Feature pills --}}
                    <div class="anim-fade-up delay-300 flex flex-wrap gap-3 mt-10">
                        @php
                            $pills = [
                                ['fa-layer-group',    'Terpusat', 'Seluruh proses NCR dalam satu platform'],
                                ['fa-location-dot',   'Terlacak', 'Monitoring status & tindak lanjut lebih jelas'],
                                ['fa-bolt',           'Efisien',  'Notifikasi & alur kerja lebih cepat'],
                            ];
                        @endphp
                        @foreach ($pills as $i => $pill)
                        <div class="brand-pill border border-slate-200 bg-white rounded-2xl px-4 py-3.5 shadow-sm cursor-default"
                             style="animation-delay: {{ 0.35 + $i * 0.08 }}s;">
                            <div class="flex items-center gap-2.5 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                    <i class="pill-icon fa-solid {{ $pill[0] }} text-[11px] text-slate-500"></i>
                                </div>
                                <span class="text-[12.5px] font-semibold text-[#0b1d3e]">{{ $pill[1] }}</span>
                            </div>
                            <p class="text-[11.5px] font-light text-slate-400 leading-relaxed pl-9">{{ $pill[2] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Bottom stats --}}
                <div class="anim-fade-up delay-400 flex items-center gap-8 pt-6 border-t border-slate-100">
                    <div>
                        <div class="text-xl font-['DM_Serif_Display'] text-[#0b1d3e]">NCR</div>
                        <div class="text-[11px] text-slate-400 font-light mt-0.5">Non Conformance Report</div>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div>
                        <div class="text-xl font-['DM_Serif_Display'] text-[#0b1d3e]">Online</div>
                        <div class="text-[11px] text-slate-400 font-light mt-0.5">Akses kapan & di mana saja</div>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div>
                        <div class="text-xl font-['DM_Serif_Display'] text-[#0b1d3e]">Realtime</div>
                        <div class="text-[11px] text-slate-400 font-light mt-0.5">Notifikasi & tracking status</div>
                    </div>
                </div>
            </div>

            {{-- ─── Right: Slot ─── --}}
            <div class="flex items-center justify-center px-6 py-12 sm:px-12">
                {{ $slot }}
            </div>

        </div>

    </body>
</html>
