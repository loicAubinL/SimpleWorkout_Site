@extends('layouts.app')

@section('title', __('interface.public_workouts'))

@section('content')
<h1>{{ __('interface.public_workouts') }}</h1>
@include('workout-splits._liste-publique')
{{ $splits->links('pagination::simple-default') }}
@endsection