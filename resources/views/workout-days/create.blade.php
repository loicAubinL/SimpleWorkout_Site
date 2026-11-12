@extends('layouts.app')

@section('title', __('interface.add_day'))

@section('content')
<a href="{{ route('workout-splits.show', $split) }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $split->name }}</a>
<div class="entete-page">
    <div>
        <span class="surtitre">{{ $split->name }}</span>
        <h1>{{ __('interface.add_day') }}</h1>
    </div>
</div>

<form method="post" action="{{ route('workout-splits.days.store', $split) }}" class="formulaire carte">
    @include('workout-days._form')
</form>
@endsection
