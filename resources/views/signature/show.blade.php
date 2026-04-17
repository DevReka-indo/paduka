<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Tanda Tangan</title>

    <link rel="icon" href="{{ asset('img/logo-paduka.svg') }}">

    {{-- Font --}}
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    {{-- Tailwind / Vite --}}
    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-xl bg-white shadow-lg rounded-2xl p-6">

        {{-- Header --}}
        <div class="text-center mb-6">
            <img src="{{ asset('img/logo-black.png') }}" alt="Logo" class="mx-auto h-12 mb-3">
            <h1 class="text-xl font-bold text-gray-800">
                Validasi Tanda Tangan Digital
            </h1>
        </div>

        {{-- Status --}}
        <div class="text-center mb-4">
            <span class="inline-block bg-green-100 text-green-700 px-4 py-1 rounded-full font-semibold">
                ✔ VALID
            </span>
        </div>

        {{-- Data --}}
        <div class="space-y-3 text-sm">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Nomor NCR</span>
                <span class="font-medium">{{ $ncr->nomor_ncr }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Jenis</span>
                <span class="font-medium">{{ $signature['label'] }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Nama</span>
                <span class="font-medium">{{ $signature['name'] }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Jabatan</span>
                <span class="font-medium">{{ $signature['jabatan'] }}</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Waktu</span>
                <span class="font-medium">{{ $signature['timestamp_formatted'] }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Status NCR</span>
                <span class="font-medium">{{ $ncr->keterangan ?? '-' }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-6 text-center text-xs text-gray-500">
            Signature ini divalidasi oleh sistem
            <strong>PADUKA</strong><br>
            <a href="https://paduka-new.ptrekaindo.co.id" class="text-blue-500" target="_blank">
                paduka-new.ptrekaindo.co.id
            </a>
        </div>

    </div>

</body>
</html>
