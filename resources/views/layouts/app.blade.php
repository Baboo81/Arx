<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ARX | Plate-forme SOC')</title>
    <meta name="description" content="@yield('meta_description', 'Protection du réseau domestique')">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- favicon --}}
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">


    <!-- Canonical -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Twitter -->
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/img/og-image.jpg') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('meta_description')">
    <meta name="twitter:image" content="{{ asset('assets/img/og-image.jpg') }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/main.js', 'resources/js/app.js'])

    {{-- Styles spécifiques --}}
    @stack('styles')
</head>

<body>
    {{-- Nav --}}
    @include('partials.nav')

    {{-- Contenu principal --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Lucide --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Scripts spécifiques --}}
    @stack('scripts')
</body>

</html>