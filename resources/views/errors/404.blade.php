@extends('layouts.app')

@section('title', __('interface.not_found_title'))

@section('content')
<x-etat-vide icone="search">
    <h1>{{ __('interface.not_found_title') }}</h1>
    <p>{{ __('interface.not_found_text') }}</p>
    <a href="{{ route('accueil') }}" class="bouton">{{ __('interface.back_home') }}</a>
</x-etat-vide>
@endsection
