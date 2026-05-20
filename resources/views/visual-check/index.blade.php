@extends('layouts.app')

@section('header')
    VISIQ
@endsection

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 w-full max-w-none mx-auto">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    VISIQ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Visual Intelligence for Quality Assurance
                </p>
            </div>

            <iframe
                src="https://visual-check.ptrekaindo.co.id"
                class="w-full border-0 block"
                style="height: calc(100vh - 190px);"
                allowfullscreen>
            </iframe>

        </div>

    </div>
@endsection
