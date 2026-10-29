@extends('layouts.app')

@section('title', __('interface.edit_title'))

@section('content')
<h1>{{ __('interface.edit_title') }}</h1>
<form method="post" action="{{ route('workout-splits.update', $split) }}" class="formulaire" enctype="multipart/form-data">
    @method('PATCH')
    @include('workout-splits._form')
</form>

@if ($split->program_path)
<form id="supprimer-programme" method="post"
    action="{{ route('workout-splits.program.destroy', $split) }}"
    data-confirm="{{ __('interface.confirm_remove_file') }}"
    onsubmit="return confirm(this.dataset.confirm)">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection