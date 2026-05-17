<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    {{-- Tailwind CSS (brug CDN hvis du ikke allerede har det kompileret via Vite/Mix) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Fonts --}}
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body >


    {{-- Flash beskeder --}}
    @if(session('success'))
        <div class="max-w-3xl mx-auto mt-6 px-4">
            <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-3xl mx-auto mt-6 px-4">
            <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Sideindhold --}}
    <main >
        @yield('content')
    </main>

    {{-- Footer --}}
 

</body>
</html>
