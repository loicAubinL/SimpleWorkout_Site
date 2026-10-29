@extends('layouts.app')

@section('title', __('interface.create_title'))

@section('content')
<h1>{{ __('interface.create_title') }}</h1>
<form method="post" action="{{ route('workout-splits.store') }}" class="formulaire" enctype="multipart/form-data">
    @include('workout-splits._form', ['split' => null])
</form>
@endsection