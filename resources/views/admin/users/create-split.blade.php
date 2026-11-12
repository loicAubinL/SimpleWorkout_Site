@extends('layouts.app')

@section('title', __('interface.create_title'))

@section('content')
<a href="{{ route('admin.users.show', $compte) }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $compte->name }}</a>
<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.admin') }} · {{ $compte->name }}</span>
        <h1>{{ __('interface.create_title') }}</h1>
        <p class="sous-titre">{{ __('interface.create_text') }}</p>
    </div>
</div>

<form method="post" action="{{ route('admin.users.splits.store', $compte) }}" class="formulaire carte">
    @include('workout-splits._form', ['split' => null])
</form>
@endsection
