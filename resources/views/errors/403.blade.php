@extends('layouts.app')

@section('title', __('interface.forbidden_title'))

@section('content')
<x-etat-vide icone="lock">
    <h1>{{ __('interface.forbidden_title') }}</h1>
    <p>{{ __('interface.forbidden_text') }}</p>
    <a href="{{ route('accueil') }}" class="bouton">{{ __('interface.back_home') }}</a>
</x-etat-vide>
@endsection
