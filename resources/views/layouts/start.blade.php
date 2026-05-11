<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
    <head>
        <title>{{ config('app.name') }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="theme-color" content="#27272a">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        @livewireStyles
        @fluxAppearance
        @vite('resources/css/app.css')
        @vite('resources/css/theme.css')
        @vite('resources/js/app.js')
    </head>
    <body class="flex w-full h-full">
        <x-sidebar-start />
        <div class="grid grid-rows-[auto_1fr] w-full h-full">
            @include('header')
            <main class="h-full flex-1 overflow-x-hidden overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
        @fluxScripts
    </body>
</html>