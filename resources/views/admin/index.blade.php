@extends('layouts.app')

@section('title', __('interface.admin'))

@section('content')
<h1>{{ __('interface.admin') }}</h1>
<p>{{ __('interface.admin_text') }}</p>

<ul>
    @foreach ($users as $user)
    <li>
        <a>
            <span>
                {{ $user->name }}
                <span class="exercice-details">{{ $user->email }}</span>
            </span>
            <span class="badge">
                {{ $user->role === \App\Models\User::ROLE_MANAGER ? __('interface.role_manager') : __('interface.role_member') }}
                · {{ __('interface.workouts_count', ['count' => $user->workout_splits_count]) }}
            </span>
        </a>
    </li>
    @endforeach
</ul>

{{ $users->links('pagination::simple-default') }}
@endsection