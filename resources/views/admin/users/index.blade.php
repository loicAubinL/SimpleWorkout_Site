@extends('layouts.app')

@section('title', __('interface.admin'))

@section('content')
<div class="entete-page">
    <div>
        <span class="surtitre"><x-icon name="shield" :size="14" /> {{ __('interface.admin') }}</span>
        <h1>{{ __('interface.accounts') }}</h1>
        <p class="sous-titre">{{ trans_choice('interface.admin_text', $total) }}</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bouton"><x-icon name="user-plus" :size="18" /> {{ __('interface.create_account') }}</a>
</div>

<form method="get" action="{{ route('admin.users.index') }}" class="recherche-barre recherche-admin" role="search">
    <label for="q" class="sr-only">{{ __('interface.search_accounts') }}</label>
    <div class="champ-icone">
        <x-icon name="search" :size="18" />
        <input id="q" name="q" type="search" value="{{ $terme }}" maxlength="100" placeholder="{{ __('interface.search_accounts') }}">
    </div>
    <label for="role" class="sr-only">{{ __('interface.role') }}</label>
    <select id="role" name="role">
        <option value="">{{ __('interface.all_roles') }}</option>
        <option value="member" @selected($role === 'member')>{{ __('interface.role_member') }}</option>
        <option value="manager" @selected($role === 'manager')>{{ __('interface.role_manager') }}</option>
    </select>
    <button type="submit" class="bouton bouton-secondaire">{{ __('interface.search_button') }}</button>
</form>

@if ($users->isEmpty())
<x-etat-vide icone="users"><p>{{ __('interface.no_results') }}</p></x-etat-vide>
@else
<div class="tableau-conteneur">
    <table>
        <caption class="sr-only">{{ __('interface.accounts') }}</caption>
        <thead>
            <tr>
                <th scope="col">{{ __('interface.name') }}</th>
                <th scope="col">{{ __('interface.role') }}</th>
                <th scope="col" class="nombre">{{ __('interface.workouts') }}</th>
                <th scope="col" class="nombre">{{ __('interface.total_sets') }}</th>
                <th scope="col"><span class="sr-only">{{ __('interface.actions') }}</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $compte)
            <tr>
                <td>
                    <a href="{{ route('admin.users.show', $compte) }}" class="titre">{{ $compte->name }}</a>
                    @if ($compte->is(auth()->user()))<span class="badge">{{ __('interface.you') }}</span>@endif
                    <div class="texte-discret petit">{{ $compte->email }}</div>
                </td>
                <td>
                    <span @class(['badge', 'badge-contour' => $compte->isManager()])>
                        {{ $compte->isManager() ? __('interface.role_manager') : __('interface.role_member') }}
                    </span>
                </td>
                <td class="nombre">{{ $compte->workout_splits_count }}</td>
                <td class="nombre">{{ $compte->logged_sets_count }}</td>
                <td>
                    <div class="actions-ligne">
                        <a href="{{ route('admin.users.show', $compte) }}" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.view') }}"><x-icon name="eye" :size="16" /><span class="sr-only">{{ __('interface.view') }} : {{ $compte->name }}</span></a>
                        <a href="{{ route('admin.users.edit', $compte) }}" class="bouton bouton-fantome bouton-icone" title="{{ __('interface.edit') }}"><x-icon name="pencil" :size="16" /><span class="sr-only">{{ __('interface.edit') }} : {{ $compte->name }}</span></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{ $users->links('pagination::simple-default') }}
@endsection
