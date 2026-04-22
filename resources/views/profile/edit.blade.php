@extends('layouts.app')

@section('header')
    Profil Saya
@endsection

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    {{-- Update Profile Info --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Informasi Profil</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Perbarui foto, nama, dan data profil kamu</p>
            </div>
        </div>
        <div class="p-6">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- Update Password --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="w-8 h-8 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Ubah Password</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">Gunakan password yang kuat untuk keamanan akun</p>
            </div>
        </div>
        <div class="p-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-red-100 dark:border-red-800/40 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-red-100 dark:border-red-800/40 bg-red-50 dark:bg-red-900/20">
            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-red-700 dark:text-red-400">Hapus Akun</h3>
                <p class="text-xs text-red-400 dark:text-red-500">Tindakan ini tidak dapat dibatalkan</p>
            </div>
        </div>
        <div class="p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
