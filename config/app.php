<?php

return [

    'name' => env('APP_NAME', 'PADUKA'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'https://paduka-new.ptrekaindo.co.id'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Untuk aplikasi Indonesia, default paling umum adalah Asia/Jakarta (WIB).
    | Jika aplikasi nanti perlu mendukung zona waktu berbeda per user,
    | timezone ini tetap bisa dijadikan default sistem.
    |
    */
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | Gunakan locale Indonesia agar translation, Carbon, validator,
    | dan formatting lebih sesuai kebutuhan aplikasi lokal.
    |
    */
    'locale' => env('APP_LOCALE', 'id'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'id'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
