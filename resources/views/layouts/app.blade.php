<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mon Application Workout')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @stack('scripts')
</head>

<body>
    <header>
        <nav>
            <a href="{{ route('accueil') }}">Accueil</a>
            <a href="{{ route('workout-splits.index') }}">Mes workouts</a>
            <a href="{{ route('recits') }}">Récits</a>
        </nav>
    </header>

    <main>
        @if (session('status'))
        <p class="message-succes" role="status">{{ session('status') }}</p>
        @endif
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} - Mon Application Workout</p>
    </footer>
</body>

</html>