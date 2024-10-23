<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">



    <title>{{ config('app.name', 'HORARIOEPIS') }}</title>

    <!-- Fonts -->
    <!-- Cargar los archivos CSS y JS de Vite -->




    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />

    @livewireStyles
</head>

<body class="font-sans antialiased"id="global">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900  bg-blue-100" id="global">
        @if (auth()->check() && auth()->user()->usertype == 'admin')
            @include('layouts.navigation')
        @elseif (auth()->check() && auth()->user()->usertype == 'user')
            @include('layouts.navigation')
        @else
        @endif
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-green-400 dark:bg-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 ">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>

</html>
