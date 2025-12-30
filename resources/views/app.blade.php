<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'docDMS') }}</title>

        @php
            \Illuminate\Support\Facades\Vite::useCspNonce(csp_nonce());
        @endphp
        <!-- Scripts -->
        @routes(true, csp_nonce())
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-baseColor-50 h-full w-full">
        @inertia
    </body>
</html>
