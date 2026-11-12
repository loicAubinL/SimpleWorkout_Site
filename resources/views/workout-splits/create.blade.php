@extends('layouts.app')

@section('title', __('interface.create_title'))

@section('content')
<a href="{{ route('workout-splits.index') }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ __('interface.my_workouts') }}</a>
<div class="entete-page">
    <div>
        <h1>{{ __('interface.create_title') }}</h1>
        <p class="sous-titre">{{ __('interface.create_text') }}</p>
    </div>
</div>

<form method="post" action="{{ route('workout-splits.store') }}" class="formulaire carte">
    @include('workout-splits._form', ['split' => null])
</form>
@endsection
