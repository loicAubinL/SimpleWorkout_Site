@extends('layouts.app')

@section('title', 'Modifier ' . $split->name)

@section('content')
<h1>Modifier un workout</h1>
<form method="post" action="{{ route('workout-splits.update', $split) }}" class="formulaire">
    @method('PATCH')
    @include('workout-splits._form')
</form>
@endsection