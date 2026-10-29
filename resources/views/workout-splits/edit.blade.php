@extends('layouts.app')

@section('title', 'Modifier ' . $split->name)

@section('content')
<h1>Modifier un workout</h1>
<form method="post" action="{{ route('workout-splits.update', $split) }}" class="formulaire" enctype="multipart/form-data">
    @method('PATCH')
    @include('workout-splits._form')
</form>
@if ($split->program_path)
<form id="supprimer-programme" method="post"
    action="{{ route('workout-splits.program.destroy', $split) }}"
    onsubmit="return confirm('Retirer le fichier du programme ?')">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection