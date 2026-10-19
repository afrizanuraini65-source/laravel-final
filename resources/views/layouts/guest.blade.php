<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-pikr-dark">
    <div class="flex min-h-screen flex-col items-center justify-center bg-pikr-dark relative overflow-hidden py-12 px-4 sm:px-6">
        
        <!-- Ambient Background Glow -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-pikr-emerald/30 blur-[120px]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] rounded-full bg-pikr-primary/20 blur-[150px]"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center w-full">
            <a href="/" class="mb-8 flex items-center gap-4 group">
                <img src="/images/lgo_genre.png" alt="GenRe Logo" class="h-16 w-16 object-contain transition-transform duration-300 group-hover:scale-105" />
                <img src="/images/lgo_smc.png" alt="SMC Logo" class="h-16 w-16 object-contain transition-transform duration-300 group-hover:scale-105" />
                <img src="/images/lgo_pikr.png" alt="PIK-R Logo" class="h-16 w-16 object-contain transition-transform duration-300 group-hover:scale-105" />
            </a>

            <div class="w-full overflow-hidden bg-white/10 backdrop-blur-xl border border-white/20 px-8 py-10 shadow-2xl sm:max-w-md rounded-3xl">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
