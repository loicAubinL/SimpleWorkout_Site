<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0E1013">
    <title>@hasSection('title')@yield('title') · @endif{{ config('app.name') === 'Laravel' ? 'SimpleWorkout' : config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preload" href="{{ asset('fonts/barlow-condensed-700.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @stack('scripts')
</head>

<body>
    <a href="#contenu" class="lien-evitement">{{ __('interface.skip_to_content') }}</a>

    <header class="entete">
        <div class="entete-interieur">
            <x-logo />

            <nav class="nav-principale" aria-label="{{ __('interface.main_nav') }}">
                @include('layouts._nav-liens')
            </nav>
            @include('layouts._nav-compte', ['suffixe' => 'bureau'])

            <details class="menu-mobile">
                <summary aria-label="{{ __('interface.menu') }}">
                    <x-icon name="menu" class="icone-ouvrir" />
                    <x-icon name="x" class="icone-fermer" />
                </summary>
                <div class="menu-mobile-panneau">
                    <nav class="pile" aria-label="{{ __('interface.main_nav') }}">
                        @include('layouts._nav-liens')
                    </nav>
                    <hr>
                    @include('layouts._nav-compte', ['suffixe' => 'mobile'])
                </div>
            </details>
        </div>
    </header>

    <main id="contenu">
        @if (session('status'))
        <p class="message message-succes" role="status">
            <x-icon name="circle-check" />
            <span>{{ session('status') }}</span>
        </p>
        @endif
        @yield('content')
    </main>

    <footer class="pied">
        <div class="pied-interieur">
            <p>&copy; {{ date('Y') }} SimpleWorkout · {{ __('interface.footer') }}</p>
            <p>
                <a href="{{ route('recits') }}">{{ __('interface.nav_stories') }}</a>
                · {{ __('interface.credits') }}
            </p>
        </div>
    </footer>
</body>

</html>
