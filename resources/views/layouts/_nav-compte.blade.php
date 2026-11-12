<div class="nav-compte">
    @auth
    <a href="{{ route('account.show') }}" @class(['nav-lien', 'actif' => request()->routeIs('account.*')])>
        <x-icon name="user" :size="18" />
        {{ auth()->user()->name }}
        @if (auth()->user()->isManager())
        <span class="badge badge-contour">{{ __('interface.role_manager') }}</span>
        @endif
    </a>
    <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.logout') }}">
            <x-icon name="log-out" :size="18" />
            <span class="sr-only">{{ __('interface.logout') }}</span>
        </button>
    </form>
    @else
    <a href="{{ route('login') }}" class="bouton bouton-fantome bouton-petit">{{ __('interface.login_title') }}</a>
    <a href="{{ route('register') }}" class="bouton bouton-petit">{{ __('interface.register_title') }}</a>
    @endauth

    <form method="post" action="{{ route('locale.update') }}" class="choix-langue">
        @csrf
        <label for="locale-{{ $suffixe }}" class="sr-only">{{ __('interface.language') }}</label>
        <select id="locale-{{ $suffixe }}" name="locale" onchange="this.form.submit()">
            <option value="fr" @selected(app()->getLocale() === 'fr')>FR</option>
            <option value="en" @selected(app()->getLocale() === 'en')>EN</option>
        </select>
        <noscript><button type="submit" class="bouton bouton-petit bouton-secondaire">{{ __('interface.apply') }}</button></noscript>
    </form>
</div>
