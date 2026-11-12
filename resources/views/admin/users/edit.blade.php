@extends('layouts.app')

@section('title', __('interface.edit_account'))

@section('content')
<a href="{{ route('admin.users.show', $compte) }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ $compte->name }}</a>
<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.admin') }}</span>
        <h1>{{ __('interface.edit_account') }}</h1>
    </div>
</div>

<form method="post" action="{{ route('admin.users.update', $compte) }}" class="formulaire carte">
    @method('PATCH')
    @include('admin.users._form')
</form>
@endsection
