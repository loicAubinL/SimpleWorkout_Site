@extends('layouts.app')

@section('title', $split->name)

@section('content')
<p><a href="{{ route('workout-splits.index') }}">{{ __('interface.back_to_list') }}</a></p>

<h1>{{ $split->name }}</h1>
<p><span class="badge">{{ $split->is_active ? __('interface.active') : __('interface.inactive') }}</span></p>

<a href="{{ route('workout-splits.edit', $split) }}" class="bouton">{{ __('interface.edit') }}</a>

<form method="post" action="{{ route('workout-splits.destroy', $split) }}"
    data-confirm="{{ __('interface.confirm_delete') }}"
    onsubmit="return confirm(this.dataset.confirm)">
    @csrf
    @method('DELETE')
    <button type="submit" class="bouton-danger">{{ __('interface.delete') }}</button>
</form>

<h2>{{ __('interface.program') }}</h2>
@if ($split->program_path)
<a href="{{ Storage::disk('public')->url($split->program_path) }}" download class="bouton">
    {{ __('interface.download_program') }}
</a>
@if ($apercu)
<pre class="apercu-json">{{ $apercu }}</pre>
@endif
@else
<p class="etat-vide">{{ __('interface.no_program') }}</p>
@endif

<h2>{{ __('interface.days') }}</h2>
@forelse ($split->workoutDays as $day)
<div class="carte-exercice">
    <span class="exercice-nom">{{ $day->name }}</span>
    <span class="exercice-details">· {{ $day->is_rest_day ? __('interface.rest_day') : __('interface.training_day') }}</span>
</div>
@empty
<p class="etat-vide">{{ __('interface.no_days') }}</p>
@endforelse
@endsection