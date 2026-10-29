<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('interface.footer'))</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @stack('scripts')
</head>

<body>
    <header>
        <nav>
            <a href="{{ route('accueil') }}">{{ __('interface.nav_home') }}</a>
            <a href="{{ route('workout-splits.index') }}">{{ __('interface.nav_workouts') }}</a>
            <a href="{{ route('recits') }}">{{ __('interface.nav_stories') }}</a>

            <form method="post" action="{{ route('locale.update') }}" class="choix-langue">
                @csrf
                <label for="locale">{{ __('interface.language') }}</label>
                <select id="locale" name="locale">
                    <option value="fr" @selected(app()->getLocale() === 'fr')>Français</option>
                    <option value="en" @selected(app()->getLocale() === 'en')>English</option>
                </select>
                <button type="submit">{{ __('interface.apply') }}</button>
            </form>
        </nav>
    </header>

    <main>
        @if (session('status'))
        <p class="message-succes" role="status">{{ session('status') }}</p>
        @endif
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} - {{ __('interface.footer') }}</p>
    </footer>
</body>

</html>