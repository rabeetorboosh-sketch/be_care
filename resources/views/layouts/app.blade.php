<!DOCTYPE html>
<html   lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
        <link href="{{asset('css/main.css')}}" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased">


    <div class="min-h-screen flex ">
        <!-- 🚀 الزر لفتح/إغلاق السايدبار -->
        <button id="toggleSidebar" class="toggle-btn">☰</button>

        @include('layouts.sidebar')

            <!-- Page Heading -->
        <div class="flex-1">


            @isset($header)
                <header class="dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        <div>
                            {{ $header }}
                        </div>
                        <button onclick="window.history.back()"
                                class="bak-arrow">
                            <i class="fa fa-arrow-right"></i> عودة
                        </button>
                    </div>
                </header>
            @endisset

                <div class="min-h-screen bg-gray-100">


                    <main>
                        {{ $slot }}
                    </main>
                </div>
        </div>


        </div>
    </body>
</html>
