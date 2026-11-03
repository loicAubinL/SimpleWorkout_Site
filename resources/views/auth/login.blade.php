@extends('layouts.app')

@section('title', __('interface.login_title'))

@section('content')
<div class="carte-auth">
    <h1>{{ __('interface.login_title') }}</h1>
    <p class="sous-titre">{{ __('interface.login_subtitle') }}</p>

    <form method="post" action="{{ route('login.store') }}" class="formulaire">
        @csrf

        <label for="email">{{ __('interface.email') }}</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
            @error('email') aria-invalid="true" aria-describedby="email-erreur" @enderror>
        @error('email') <p id="email-erreur" class="message-erreur" role="alert">{{ $message }}</p> @enderror

        <label for="password">{{ __('interface.password') }}</label>
        <input id="password" name="password" type="password" required>

        <button type="submit" class="bouton-plein">{{ __('interface.login_button') }}</button>
    </form>

    <p class="lien-bas"><a href="{{ route('register') }}">{{ __('interface.no_account') }}</a></p>
</div>
@endsection