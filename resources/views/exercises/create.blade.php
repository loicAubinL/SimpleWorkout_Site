@extends('layouts.app')

@section('title', __('interface.add_exercise'))

@section('content')
<a href="{{ route('workout-splits.show', $jour->workout_split_id) }}#jour-{{ $jour->id }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $jour->workoutSplit->name }}</a>
<div class="entete-page">
    <div>
        <span class="surtitre">{{ $jour->workoutSplit->name }} · {{ $jour->name }}</span>
        <h1>{{ __('interface.add_exercise') }}</h1>
    </div>
</div>

<form method="post" action="{{ route('days.exercises.store', $jour) }}" class="formulaire carte">
    @include('exercises._form')
</form>
@endsection
