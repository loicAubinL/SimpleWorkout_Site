@extends('layouts.app')

@section('title', __('interface.forbidden_title'))

@section('content')
<h1>{{ __('interface.forbidden_title') }}</h1>
<p class="message-erreur" role="alert">{{ __('interface.forbidden_text') }}</p>
<a href="{{ route('accueil') }}" class="bouton">{{ __('interface.back_home') }}</a>
@endsection