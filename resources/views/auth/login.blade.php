@extends('layouts.app')

@section('title', __('interface.login_title'))

@section('content')
    <h1>{{ __('interface.login_title') }}</h1>

    <form method="post" action="{{ route('login.store') }}" class="formulaire">
        @csrf

        <label for="email">{{ __('interface.email') }}</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
               @error('email') aria-invalid="true" aria-describedby="email-erreur" @enderror>
        @error('email') <p id="email-erreur" class="message-erreur" role="alert">{{ $message }}</p> @enderror

        <label for="password">{{ __('interface.password') }}</label>
        <input id="password" name="password" type="password" required>

        <div class="actions">
            <button type="submit">{{ __('interface.login_button') }}</button>
            <a href="{{ route('register') }}">{{ __('interface.no_account') }}</a>
        </div>
    </form>
@endsection