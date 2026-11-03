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
        <nav aria-label="{{ __('interface.main_nav') }}">
            <div class="nav-liens">
                <a href="{{ route('accueil') }}" class="nav-marque">SimpleWorkout</a>
                <a href="{{ route('workouts.publics') }}" @class(['actif'=> request()->routeIs('workouts.publics')])>{{ __('interface.public_workouts') }}</a>
                @auth
                <a href="{{ route('workout-splits.index') }}" @class(['actif'=> request()->routeIs('workout-splits.*')])>{{ __('interface.nav_workouts') }}</a>
                <a href="{{ route('account.show') }}" @class(['actif'=> request()->routeIs('account.show')])>{{ __('interface.my_account') }}</a>
                @if (auth()->user()->role === \App\Models\User::ROLE_MANAGER)
                <a href="{{ route('admin.index') }}" @class(['actif'=> request()->routeIs('admin.*')])>{{ __('interface.admin') }}</a>
                @endif
                @endauth
                <a href="{{ route('recits') }}" @class(['actif'=> request()->routeIs('recits')])>{{ __('interface.nav_stories') }}</a>
            </div>

            <div class="nav-compte">
                @auth
                <span class="nav-utilisateur">
                    {{ auth()->user()->name }}
                    @if (auth()->user()->role === \App\Models\User::ROLE_MANAGER)
                    <span class="nav-role">{{ __('interface.role_manager') }}</span>
                    @endif
                </span>
                <form method="post" action="{{ route('logout') }}" class="nav-deconnexion">
                    @csrf
                    <button type="submit">{{ __('interface.logout') }}</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="nav-lien-secondaire">{{ __('interface.login_title') }}</a>
                <a href="{{ route('register') }}" class="nav-bouton">{{ __('interface.register_title') }}</a>
                @endauth

                <form method="post" action="{{ route('locale.update') }}" class="choix-langue">
                    @csrf
                    <label for="locale" class="sr-only">{{ __('interface.language') }}</label>
                    <select id="locale" name="locale" onchange="this.form.submit()">
                        <option value="fr" @selected(app()->getLocale() === 'fr')>FR</option>
                        <option value="en" @selected(app()->getLocale() === 'en')>EN</option>
                    </select>
                    <noscript><button type="submit">{{ __('interface.apply') }}</button></noscript>
                </form>
            </div>
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