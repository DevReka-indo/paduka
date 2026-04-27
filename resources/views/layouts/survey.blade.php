<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kepuasan Pelanggan – PT. Rekaindo Global Jasa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Dot-grid background */
        body {
            min-height: 100vh;
            background-color: #F0EFF9;
            background-image: radial-gradient(circle, #C5C0E8 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Fade top & bottom so dots don't feel harsh */
        body::before,
        body::after {
            content: '';
            position: fixed;
            left: 0; right: 0;
            height: 160px;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            top: 0;
            background: linear-gradient(to bottom, #F0EFF9, transparent);
        }
        body::after {
            bottom: 0;
            background: linear-gradient(to top, #F0EFF9, transparent);
        }

        .layout-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2.5rem 1rem 4rem;
        }

        .layout-inner {
            width: 100%;
            max-width: 700px;
        }
    </style>
</head>
<body>
    <div class="layout-wrap">
        <div class="layout-inner">
            @yield('content')
        </div>
    </div>
</body>
</html>
