@extends('layouts.app')

@section('title', __('interface.edit_title'))

@section('content')
<a href="{{ route('workout-splits.show', $split) }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $split->name }}</a>
<div class="entete-page">
    <div>
        <h1>{{ __('interface.edit_title') }}</h1>
    </div>
</div>

<form method="post" action="{{ route('workout-splits.update', $split) }}" class="formulaire carte">
    @method('PATCH')
    @include('workout-splits._form')
</form>
@endsection
