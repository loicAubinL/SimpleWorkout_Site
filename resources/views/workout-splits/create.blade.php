@extends('layouts.app')

@section('title', 'Créer un workout')

@section('content')
<h1>Créer un workout</h1>
<form method="post" action="{{ route('workout-splits.store') }}" class="formulaire" enctype="multipart/form-data">
    @include('workout-splits._form', ['split' => null])
</form>
@endsection