@extends('layouts.app')

@section('title', __('interface.login_title'))

@section('content')
<div class="carte carte-auth">
    <x-logo-mark :size="44" class="logo-marque" />
    <h1>{{ __('interface.login_title') }}</h1>
    <p class="sous-titre">{{ __('interface.login_subtitle') }}</p>

    <form method="post" action="{{ route('login.store') }}" class="formulaire">
        @csrf
        <x-champ name="email" type="email" :label="__('interface.email')" required autofocus autocomplete="email" />
        <x-champ name="password" type="password" :label="__('interface.password')" required autocomplete="current-password" />

        <label class="case-a-cocher">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <span>{{ __('interface.remember_me') }}</span>
        </label>

        <button type="submit" class="bouton bouton-large">
            <x-icon name="log-in" :size="18" /> {{ __('interface.login_button') }}
        </button>
    </form>

    <p class="lien-bas">{{ __('interface.no_account_yet') }} <a href="{{ route('register') }}">{{ __('interface.no_account') }}</a></p>
</div>
@endsection
