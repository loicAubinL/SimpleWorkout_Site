@extends('layouts.app')

@section('title', __('interface.register_title'))

@section('content')
<div class="carte carte-auth">
    <x-logo-mark :size="44" class="logo-marque" />
    <h1>{{ __('interface.register_title') }}</h1>
    <p class="sous-titre">{{ __('interface.register_subtitle') }}</p>

    <form method="post" action="{{ route('register.store') }}" class="formulaire">
        @csrf
        <x-champ name="name" :label="__('interface.name')" required autofocus autocomplete="name" maxlength="100" />
        <x-champ name="email" type="email" :label="__('interface.email')" required autocomplete="email" />
        <x-champ name="email_confirmation" type="email" :label="__('interface.email_confirmation')" required autocomplete="email" />
        <x-champ name="password" type="password" :label="__('interface.password')" :aide="__('interface.password_hint')" required autocomplete="new-password" />
        <x-champ name="password_confirmation" type="password" :label="__('interface.password_confirmation')" required autocomplete="new-password" />

        <div>
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}" data-theme="dark"></div>
            @error('g-recaptcha-response')
            <p class="message-erreur" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bouton bouton-large">
            <x-icon name="user-plus" :size="18" /> {{ __('interface.register_button') }}
        </button>
    </form>

    <p class="lien-bas">{{ __('interface.already_member') }} <a href="{{ route('login') }}">{{ __('interface.login_button') }}</a></p>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer></script>
@endpush
