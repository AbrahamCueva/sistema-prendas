@php
    use App\Traits\HasSettings;

    // Valores de la BD (con fallback)
    $siteName = HasSettings::getSetting('site_name', 'Sistema de Prendas');
    $seoTitle = HasSettings::getSetting('seo_title', $siteName);
    $seoDescription = HasSettings::getSetting(
        'seo_description',
        'Sistema de gestión y auditoría para lotes de prendas.',
    );
    $seoKeywords = HasSettings::getSetting('seo_keywords', 'lotes, prendas, costura, auditoría');
    $faviconPath = HasSettings::getSetting('favicon_path', 'favicon.ico');

    // Título final (usa slot de la vista si existe, si no, usa el SEO Title)
    $finalTitle = $title ?? null ? $title . ' | ' . $seoTitle : $seoTitle;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Sistema de prendas') }}</title> --}}
    <title>{{ $finalTitle }}</title>

    <meta name="description" content="{{ $description ?? $seoDescription }}">
    <meta name="keywords" content="{{ $keywords ?? $seoKeywords }}">
    <link rel="icon" type="image/x-icon" href="{{ asset($faviconPath) }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @stack('scripts')
</body>

</html>
