@extends('layouts.app')

@section('title', __('interface.edit_exercise'))

@section('content')
<a href="{{ route('workout-splits.show', $jour->workout_split_id) }}#jour-{{ $jour->id }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $jour->workoutSplit->name }}</a>
<div class="entete-page">
    <div>
        <span class="surtitre">{{ $jour->workoutSplit->name }} · {{ $jour->name }}</span>
        <h1>{{ __('interface.edit_exercise') }}</h1>
    </div>
</div>

<form method="post" action="{{ route('exercises.update', $exercice) }}" class="formulaire carte">
    @method('PATCH')
    @include('exercises._form')
</form>
@endsection
