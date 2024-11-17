<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">

        <x-nav-link :href="route('home')">
            <img src="{{ asset('storage/img/campigo.png') }}" alt="Campigo Logo" class="h-12 w-auto" />
        </x-nav-link>
        
        <x-nav-link :href="route('home')">
            <h1 class="text-3xl font-extrabold text-teal-700">Campigo</h1>
        </x-nav-link>
        <div class="w-full sm:max-w-md mt-3 mb-3 px-3 py-2 bg-gradient-to-r from-teal-100 via-green-100 to-blue-100 shadow-md overflow-hidden sm:rounded-2xl">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
