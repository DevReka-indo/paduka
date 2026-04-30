<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PADUKA') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo-paduka.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #060d1f;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* ── Background blobs ── */
        .bg-blob {
            position: fixed; border-radius: 50%;
            filter: blur(100px); pointer-events: none; z-index: 0;
        }
        .blob-1 { width:560px;height:560px; background:radial-gradient(circle,rgba(30,64,175,0.4),transparent 70%); top:-180px;left:-160px; }
        .blob-2 { width:400px;height:400px; background:radial-gradient(circle,rgba(6,182,212,0.18),transparent 70%); bottom:-120px;right:-120px; }
        .blob-3 { width:300px;height:300px; background:radial-gradient(circle,rgba(99,102,241,0.12),transparent 70%); top:40%;right:10%; }

        .bg-dots {
            position:fixed;inset:0;z-index:0;pointer-events:none;
            background-image:radial-gradient(circle,rgba(255,255,255,0.09) 1px,transparent 1px);
            background-size:32px 32px; opacity:0.6;
        }

        /* ── Shell ── */
        .shell {
            position: relative; z-index: 1;
            width: 100%; max-width: 980px;
            background: #ffffff;
            border-radius: 24px; overflow: hidden;
            box-shadow: 0 50px 120px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.07);
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 560px;
            opacity: 0; transform: translateY(30px);
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            background: linear-gradient(145deg, #0b1d3e 0%, #0f2d4a 60%, #0e2840 100%);
            padding: 3rem 2.75rem 2.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Wave edge on the right side of left panel */
        .wave-edge {
            position: absolute;
            right: -1px; top: 0; bottom: 0;
            width: 52px;
            z-index: 10;
            pointer-events: none;
        }
        .wave-edge svg {
            width: 100%; height: 100%;
            display: block;
        }

        /* Subtle mesh */
        .left-panel::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(circle,rgba(255,255,255,0.04) 1px,transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -100px; left: 50%; transform: translateX(-50%);
            width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(30,64,175,0.25), transparent 65%);
            border-radius: 50%; pointer-events: none;
        }

        /* System name block */
        .sys-name-block {
            position: relative; z-index: 1;
            text-align: center;
            opacity: 0;
        }
        /* Logo row — two logos side by side */
        .sys-logos-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 1rem;
        }

        .sys-logo-wrap {
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 64px; height: 64px;
            background: #ffffff;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        }
        .sys-logo-wrap img { width: 40px; height: 40px; object-fit: contain; }

        /* Vertical divider between logos */
        .logo-divider {
            width: 1px; height: 40px;
            background: rgba(255,255,255,0.18);
            margin: 0 1rem;
        }

        .sys-logo-company {
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 64px; height: 64px;
            background: #ffffff;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        }
        .sys-logo-company img { width: 40px; height: 40px; object-fit: contain; }

        .sys-title {
            font-family: 'DM Serif Display', serif;
            font-size: 36px; letter-spacing: 6px;
            color: #ffffff; line-height: 1;
            margin-bottom: 0.3rem;
        }
        .sys-title span { color: #60a5fa; }

        .sys-subtitle {
            font-size: 10px; font-weight: 400;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.12em; text-transform: uppercase;
        }

        /* ── QC ANIMATION STAGE ── */
        .anim-stage {
            position: relative; z-index: 1;
            width: 100%; flex: 1;
            display: flex; align-items: center; justify-content: center;
        }

        .anim-svg { width: 300px; height: 280px; overflow: visible; }

        /* Tagline */
        .sys-tagline {
            position: relative; z-index: 1;
            text-align: center; opacity: 0;
        }
        .sys-tagline p {
            font-size: 11px; font-weight: 300;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.04em;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            background: #ffffff;
            padding: 3rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .shell { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { padding: 2.5rem 2rem; }
        }
    </style>
</head>
<body>

    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>
    <div class="bg-dots"></div>

    <div class="shell" id="shell">

        <!-- ══════════ LEFT PANEL ══════════ -->
        <div class="left-panel">

            <!-- System Name -->
            <div class="sys-name-block" id="elSysName">
                <div class="sys-logos-row">
                    <div class="sys-logo-wrap">
                        <img src="{{ asset('img/logo-paduka.svg') }}" alt="PADUKA">
                    </div>
                    <div class="logo-divider"></div>
                    <div class="sys-logo-company">
                        <img src="{{ asset('img/logo-black.png') }}" alt="{{ config('app.name') }}">
                    </div>
                </div>
                <div class="sys-title">PAD<span>U</span>KA</div>
                <div class="sys-subtitle">Sistem NCR Online Terpusat</div>
            </div>

            <!-- QC Animation Stage -->
            <div class="anim-stage">
                <svg class="anim-svg" id="qcSvg" viewBox="0 0 300 280" fill="none" xmlns="http://www.w3.org/2000/svg">

                    <!-- ── Clipboard / NCR Document ── -->
                    <g id="clipboard">
                        <!-- Shadow -->
                        <rect x="62" y="52" width="132" height="170" rx="12" fill="rgba(0,0,0,0.3)" transform="translate(4,6)"/>
                        <!-- Body -->
                        <rect x="62" y="52" width="132" height="170" rx="12" fill="#f0f9ff" stroke="#bfdbfe" stroke-width="1.5"/>
                        <!-- Header bar -->
                        <rect x="62" y="52" width="132" height="36" rx="12" fill="#1e40af"/>
                        <rect x="62" y="74" width="132" height="14" fill="#1e40af"/>
                        <!-- Clip -->
                        <rect x="108" y="44" width="40" height="20" rx="10" fill="#1e3a8a" stroke="#93c5fd" stroke-width="1.2"/>
                        <rect x="120" y="44" width="16" height="10" rx="5" fill="#1e40af"/>
                        <!-- Header text lines -->
                        <rect x="80" y="63" width="60" height="5" rx="2.5" fill="rgba(255,255,255,0.7)"/>
                        <rect x="80" y="72" width="36" height="3.5" rx="1.75" fill="rgba(255,255,255,0.35)"/>

                        <!-- Checklist rows -->
                        <!-- Row 1 -->
                        <rect x="78" y="102" width="14" height="14" rx="3" fill="white" stroke="#bfdbfe" stroke-width="1.2" id="cb1"/>
                        <rect x="98" y="105" width="70" height="4" rx="2" fill="#94a3b8"/>
                        <rect x="98" y="113" width="50" height="3" rx="1.5" fill="#cbd5e1"/>
                        <!-- Row 2 -->
                        <rect x="78" y="126" width="14" height="14" rx="3" fill="white" stroke="#bfdbfe" stroke-width="1.2" id="cb2"/>
                        <rect x="98" y="129" width="78" height="4" rx="2" fill="#94a3b8"/>
                        <rect x="98" y="137" width="55" height="3" rx="1.5" fill="#cbd5e1"/>
                        <!-- Row 3 -->
                        <rect x="78" y="150" width="14" height="14" rx="3" fill="white" stroke="#bfdbfe" stroke-width="1.2" id="cb3"/>
                        <rect x="98" y="153" width="65" height="4" rx="2" fill="#94a3b8"/>
                        <rect x="98" y="161" width="45" height="3" rx="1.5" fill="#cbd5e1"/>
                        <!-- Row 4 -->
                        <rect x="78" y="174" width="14" height="14" rx="3" fill="white" stroke="#bfdbfe" stroke-width="1.2" id="cb4"/>
                        <rect x="98" y="177" width="72" height="4" rx="2" fill="#94a3b8"/>
                        <rect x="98" y="185" width="52" height="3" rx="1.5" fill="#cbd5e1"/>

                        <!-- Divider -->
                        <line x1="78" y1="198" x2="178" y2="198" stroke="#e2e8f0" stroke-width="1"/>

                        <!-- Status badge area -->
                        <rect x="78" y="206" width="68" height="8" rx="4" fill="#dbeafe"/>
                    </g>

                    <!-- ── Checkmarks (drawn in by anime) ── -->
                    <g id="checks" opacity="0">
                        <!-- Check 1 -->
                        <path id="chk1" d="M80 109 l4 4 6-6" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" stroke-dasharray="20" stroke-dashoffset="20"/>
                        <!-- Check 2 -->
                        <path id="chk2" d="M80 133 l4 4 6-6" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" stroke-dasharray="20" stroke-dashoffset="20"/>
                        <!-- Check 3 -->
                        <path id="chk3" d="M80 157 l4 4 6-6" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" stroke-dasharray="20" stroke-dashoffset="20"/>
                        <!-- Check 4 - X mark (non-conformance!) -->
                        <path id="chk4" d="M80 176 l5 5 m0-5 l-5 5" stroke="#ef4444" stroke-width="2" stroke-linecap="round" fill="none" stroke-dasharray="20" stroke-dashoffset="20"/>
                    </g>

                    <!-- ── Magnifier ── -->
                    <g id="magnifier" opacity="0">
                        <circle cx="218" cy="118" r="38" fill="rgba(30,64,175,0.12)" stroke="#3b82f6" stroke-width="1.5"/>
                        <circle cx="218" cy="118" r="28" fill="rgba(255,255,255,0.08)" stroke="#93c5fd" stroke-width="1"/>
                        <!-- Handle -->
                        <line x1="240" y1="140" x2="260" y2="162" stroke="#60a5fa" stroke-width="5" stroke-linecap="round"/>
                        <line x1="240" y1="140" x2="260" y2="162" stroke="#93c5fd" stroke-width="2" stroke-linecap="round"/>
                        <!-- Inner magnifier cross-hairs -->
                        <line x1="218" y1="100" x2="218" y2="136" stroke="rgba(147,197,253,0.3)" stroke-width="1" stroke-dasharray="3 3"/>
                        <line x1="200" y1="118" x2="236" y2="118" stroke="rgba(147,197,253,0.3)" stroke-width="1" stroke-dasharray="3 3"/>
                        <!-- Scan line -->
                        <line id="scanLine" x1="200" y1="106" x2="236" y2="106" stroke="rgba(96,165,250,0.6)" stroke-width="1.5" stroke-linecap="round"/>
                    </g>

                    <!-- ── NCR Alert badge ── -->
                    <g id="ncrBadge" opacity="0" transform="translate(178,190)">
                        <rect x="0" y="0" width="90" height="36" rx="10" fill="#1e40af" filter="url(#shadow)"/>
                        <rect x="0" y="0" width="90" height="36" rx="10" fill="url(#badgeGrad)"/>
                        <circle cx="18" cy="18" r="8" fill="rgba(255,255,255,0.15)"/>
                        <text x="18" y="22.5" text-anchor="middle" font-family="DM Sans, sans-serif" font-size="10" font-weight="700" fill="white">!</text>
                        <text x="54" y="15" text-anchor="middle" font-family="DM Sans, sans-serif" font-size="8" font-weight="700" fill="rgba(255,255,255,0.9)" letter-spacing="1">NCR</text>
                        <text x="54" y="26" text-anchor="middle" font-family="DM Sans, sans-serif" font-size="7.5" font-weight="300" fill="rgba(255,255,255,0.6)">Non Conformance</text>
                    </g>

                    <!-- ── Floating particles ── -->
                    <g id="particles">
                        <circle id="p1" cx="40"  cy="80"  r="3" fill="rgba(96,165,250,0.5)"/>
                        <circle id="p2" cx="265" cy="75"  r="2" fill="rgba(34,197,94,0.5)"/>
                        <circle id="p3" cx="255" cy="195" r="4" fill="rgba(147,51,234,0.35)"/>
                        <circle id="p4" cx="35"  cy="195" r="2.5" fill="rgba(251,191,36,0.5)"/>
                        <circle id="p5" cx="150" cy="35"  r="2" fill="rgba(96,165,250,0.4)"/>
                    </g>

                    <!-- ── Pulse rings around magnifier ── -->
                    <g id="pulseRings" opacity="0">
                        <circle id="ring1" cx="218" cy="118" r="46" fill="none" stroke="rgba(59,130,246,0.2)" stroke-width="1"/>
                        <circle id="ring2" cx="218" cy="118" r="56" fill="none" stroke="rgba(59,130,246,0.1)" stroke-width="1"/>
                    </g>

                    <!-- Defs -->
                    <defs>
                        <linearGradient id="badgeGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#1e40af"/>
                            <stop offset="100%" stop-color="#1d4ed8"/>
                        </linearGradient>
                        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="rgba(0,0,0,0.3)"/>
                        </filter>
                    </defs>

                </svg>
            </div>

            <!-- Tagline -->
            <div class="sys-tagline" id="elTagline">
                <p>© {{ date('Y') }} PT. Rekaindo Global Jasa · All rights reserved</p>
            </div>

            <!-- Wave edge separator -->
            <div class="wave-edge">
                <svg viewBox="0 0 52 560" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,0 C32,70 18,140 36,210 C52,270 52,290 36,350 C18,420 32,490 0,560 L52,560 L52,0 Z" fill="#ffffff"/>
                </svg>
            </div>

        </div>

        <!-- ══════════ RIGHT PANEL ══════════ -->
        <div class="right-panel" id="rightPanel">
            {{ $slot }}
        </div>

    </div>

    <!-- Anime.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        // ── 1. Card entrance ──
        anime({
            targets: '#shell',
            opacity: [0, 1],
            translateY: [30, 0],
            duration: 700,
            easing: 'easeOutExpo',
        });

        // ── 2. System name + tagline ──
        anime({
            targets: ['#elSysName', '#elTagline'],
            opacity: [0, 1],
            translateY: [16, 0],
            delay: anime.stagger(200, { start: 400 }),
            duration: 600,
            easing: 'easeOutExpo',
        });

        // ── 3. Clipboard slides in from left ──
        anime({
            targets: '#clipboard',
            opacity: [0, 1],
            translateX: [-30, 0],
            duration: 700,
            delay: 600,
            easing: 'easeOutExpo',
        });

        // ── 4. Magnifier slides in from right + pulse rings ──
        anime({
            targets: '#magnifier',
            opacity: [0, 1],
            translateX: [30, 0],
            duration: 700,
            delay: 800,
            easing: 'easeOutExpo',
            complete: () => {
                // Pulse rings expand infinitely
                anime({
                    targets: '#pulseRings',
                    opacity: [0, 1],
                    duration: 300,
                    easing: 'easeOutQuad',
                    complete: () => {
                        anime({
                            targets: '#ring1',
                            r: [46, 58],
                            opacity: [0.4, 0],
                            duration: 1800,
                            loop: true,
                            easing: 'easeOutCubic',
                        });
                        anime({
                            targets: '#ring2',
                            r: [56, 70],
                            opacity: [0.3, 0],
                            duration: 1800,
                            delay: 400,
                            loop: true,
                            easing: 'easeOutCubic',
                        });
                    }
                });
            }
        });

        // ── 5. Scan line moves inside magnifier ──
        anime({
            targets: '#scanLine',
            translateY: [-20, 20],
            opacity: [1, 0.4, 1],
            duration: 2000,
            delay: 1200,
            loop: true,
            direction: 'alternate',
            easing: 'easeInOutSine',
        });

        // ── 6. Checkmarks draw in one by one ──
        const checkDelay = 1000;
        ['#chk1','#chk2','#chk3','#chk4'].forEach((sel, i) => {
            anime({
                targets: sel,
                strokeDashoffset: [20, 0],
                opacity: [0, 1],
                duration: 400,
                delay: checkDelay + i * 380,
                easing: 'easeOutQuart',
                begin: () => {
                    document.querySelector('#checks').setAttribute('opacity','1');
                }
            });
            // Checkbox fill for checks 1-3
            if (i < 3) {
                anime({
                    targets: `#cb${i+1}`,
                    fill: ['#ffffff','#f0fdf4'],
                    stroke: ['#bfdbfe','#22c55e'],
                    duration: 300,
                    delay: checkDelay + i * 380 + 100,
                    easing: 'easeOutQuad',
                });
            } else {
                // cb4 turns red (non-conformance)
                anime({
                    targets: '#cb4',
                    fill: ['#ffffff','#fef2f2'],
                    stroke: ['#bfdbfe','#ef4444'],
                    duration: 300,
                    delay: checkDelay + i * 380 + 100,
                    easing: 'easeOutQuad',
                });
            }
        });

        // ── 7. NCR Badge pops up after last check ──
        anime({
            targets: '#ncrBadge',
            opacity: [0, 1],
            scale: [0.5, 1],
            translateY: [10, 0],
            duration: 500,
            delay: checkDelay + 4 * 380 + 200,
            easing: 'easeOutBack',
        });

        // NCR Badge subtle pulse after appearing
        setTimeout(() => {
            anime({
                targets: '#ncrBadge',
                scale: [1, 1.04, 1],
                duration: 1400,
                loop: true,
                easing: 'easeInOutSine',
            });
        }, checkDelay + 4 * 380 + 800);

        // ── 8. Floating particles ──
        ['#p1','#p2','#p3','#p4','#p5'].forEach((sel, i) => {
            anime({
                targets: sel,
                translateY: [0, -12, 0],
                opacity: [0.3, 0.8, 0.3],
                duration: 2200 + i * 400,
                delay: i * 300,
                loop: true,
                easing: 'easeInOutSine',
            });
        });

        // ── 9. Right panel children stagger ──
        anime({
            targets: '#rightPanel > *',
            opacity: [0, 1],
            translateY: [14, 0],
            delay: anime.stagger(70, { start: 500 }),
            duration: 440,
            easing: 'easeOutQuart',
        });

        // ── 10. Input focus micro-interaction ──
        document.querySelectorAll('.lf-input').forEach(inp => {
            inp.addEventListener('focus', () =>
                anime({ targets: inp, scale: [1, 1.006], duration: 180, easing: 'easeOutQuad' })
            );
            inp.addEventListener('blur', () =>
                anime({ targets: inp, scale: [1.006, 1], duration: 180, easing: 'easeOutQuad' })
            );
        });

        // ── 11. Submit button bounce ──
        const btn = document.getElementById('submitBtn');
        if (btn) {
            btn.addEventListener('click', () =>
                anime({ targets: btn, scale: [1, 0.95, 1], duration: 300, easing: 'easeOutElastic(1,.5)' })
            );
        }

    });
    </script>

</body>
</html>
