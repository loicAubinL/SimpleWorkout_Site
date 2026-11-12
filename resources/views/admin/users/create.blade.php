@extends('layouts.app')

@section('title', __('interface.create_account'))

@section('content')
<a href="{{ route('admin.users.index') }}" class="fil"><x-icon name="arrow-left" :size="16" /> {{ __('interface.accounts') }}</a>
<div class="entete-page">
    <div>
        <span class="surtitre">{{ __('interface.admin') }}</span>
        <h1>{{ __('interface.create_account') }}</h1>
    </div>
</div>

<form method="post" action="{{ route('admin.users.store') }}" class="formulaire carte">
    @include('admin.users._form')
</form>
@endsection
