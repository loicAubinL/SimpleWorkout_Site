@extends('layouts.app')

@section('title', __('interface.register_title'))

@section('content')
<div class="carte-auth">
    <h1>{{ __('interface.register_title') }}</h1>
    <p class="sous-titre">{{ __('interface.register_subtitle') }}</p>

    <form method="post" action="{{ route('register.store') }}" class="formulaire">
        @csrf

        <label for="name">{{ __('interface.name') }}</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
            @error('name') aria-invalid="true" aria-describedby="name-erreur" @enderror>
        @error('name') <p id="name-erreur" class="message-erreur" role="alert">{{ $message }}</p> @enderror

        <label for="email">{{ __('interface.email') }}</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
            @error('email') aria-invalid="true" aria-describedby="email-erreur" @enderror>
        @error('email') <p id="email-erreur" class="message-erreur" role="alert">{{ $message }}</p> @enderror

        <label for="email_confirmation">{{ __('interface.email_confirmation') }}</label>
        <input id="email_confirmation" name="email_confirmation" type="email" value="{{ old('email_confirmation') }}" required>

        <label for="password">{{ __('interface.password') }}</label>
        <input id="password" name="password" type="password" required
            @error('password') aria-invalid="true" aria-describedby="password-erreur" @enderror>
        @error('password') <p id="password-erreur" class="message-erreur" role="alert">{{ $message }}</p> @enderror

        <label for="password_confirmation">{{ __('interface.password_confirmation') }}</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>

        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        @error('g-recaptcha-response')
        <p class="message-erreur" role="alert">{{ $message }}</p>
        @enderror

        <button type="submit" class="bouton-plein">{{ __('interface.register_button') }}</button>
    </form>

    <p class="lien-bas"><a href="{{ route('login') }}">{{ __('interface.have_account') }}</a></p>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer></script>
@endpush