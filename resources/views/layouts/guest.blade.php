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

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="text-gray-900">
    <div class="min-h-screen flex bg-gradient-to-r from-teal-100 via-green-100 to-blue-100 relative justify-end">
        <!-- Background image container -->
        <div class="absolute inset-0 z-0" style="background-image: url('{{ asset('storage/img/bg3.jpg') }}'); background-size: cover; background-position: center; opacity: 0.5;"></div>
        
        <!-- Logo container with responsive positioning -->
        <div class="z-20 absolute md:top-5 md:left-5 top-10 left-1/2 transform -translate-x-1/2 md:transform-none flex items-center">
            <x-nav-link :href="route('home')">
                <img src="{{ asset('storage/img/campigo.png') }}" alt="Campigo Logo" class="h-12 w-auto" />
            </x-nav-link>
            <x-nav-link :href="route('home')">
                <h1 class="text-3xl font-extrabold text-teal-700">Campigo</h1>
            </x-nav-link>
        </div>

        <!-- Main content container -->
        <div class="w-[450px] min-h-screen shadow-md overflow-hidden z-10 rounded-l-xl" style="opacity: 0.75;">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
