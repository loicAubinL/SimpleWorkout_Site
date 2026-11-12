@extends('layouts.app')

@section('title', __('interface.edit_day'))

@section('content')
<a href="{{ route('workout-splits.show', $split) }}#jour-{{ $jour->id }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $split->name }}</a>
<div class="entete-page">
    <div>
        <span class="surtitre">{{ $split->name }}</span>
        <h1>{{ __('interface.edit_day') }}</h1>
    </div>
</div>

<form method="post" action="{{ route('days.update', $jour) }}" class="formulaire carte">
    @method('PATCH')
    @include('workout-days._form')
</form>
@endsection
