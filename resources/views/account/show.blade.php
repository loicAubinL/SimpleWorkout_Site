@extends('layouts.app')

@section('title', __('interface.my_account'))

@section('content')
<div class="entete-page">
    <div>
        <span class="surtitre">{{ $user->isManager() ? __('interface.role_manager') : __('interface.role_member') }}</span>
        <h1>{{ __('interface.my_account') }}</h1>
        <p class="sous-titre">{{ __('interface.member_since', ['date' => $user->created_at->locale(app()->getLocale())->isoFormat('LL')]) }}</p>
    </div>
</div>

<div class="stats">
    <a href="{{ route('workout-splits.index') }}" class="carte stat lien-carte">
        <span class="stat-valeur">{{ $user->workout_splits_count }}</span>
        <span class="stat-libelle">{{ __('interface.my_workouts') }}</span>
    </a>
    <a href="{{ route('saved.index') }}" class="carte stat lien-carte">
        <span class="stat-valeur">{{ $user->saved_workouts_count }}</span>
        <span class="stat-libelle">{{ __('interface.nav_saved') }}</span>
    </a>
    <a href="{{ route('history.index') }}" class="carte stat lien-carte">
        <span class="stat-valeur">{{ $user->logged_sets_count }}</span>
        <span class="stat-libelle">{{ __('interface.total_sets') }}</span>
    </a>
</div>

<div class="grille-2">
    <section class="carte" aria-labelledby="titre-profil">
        <h2 id="titre-profil">{{ __('interface.profile') }}</h2>
        <form method="post" action="{{ route('account.update') }}" class="formulaire">
            @csrf
            @method('PATCH')
            <x-champ name="name" :label="__('interface.name')" :value="$user->name" required maxlength="100" autocomplete="name" />
            <x-champ name="email" type="email" :label="__('interface.email')" :value="$user->email" required autocomplete="email" />
            <div class="actions">
                <button type="submit" class="bouton"><x-icon name="save" :size="18" /> {{ __('interface.save') }}</button>
            </div>
        </form>
    </section>

    <section class="carte" aria-labelledby="titre-mdp">
        <h2 id="titre-mdp">{{ __('interface.change_password') }}</h2>
        <form method="post" action="{{ route('account.password') }}" class="formulaire">
            @csrf
            @method('PUT')
            <x-champ name="current_password" type="password" :label="__('interface.current_password')" required autocomplete="current-password" />
            <x-champ name="password" type="password" :label="__('interface.new_password')" :aide="__('interface.password_hint')" required autocomplete="new-password" />
            <x-champ name="password_confirmation" type="password" :label="__('interface.password_confirmation')" required autocomplete="new-password" />
            <div class="actions">
                <button type="submit" class="bouton bouton-secondaire"><x-icon name="lock" :size="18" /> {{ __('interface.change_password') }}</button>
            </div>
        </form>
    </section>
</div>

<section class="carte zone-danger section" aria-labelledby="titre-suppression">
    <h2 id="titre-suppression">{{ __('interface.delete_account') }}</h2>
    <p class="texte-discret">{{ __('interface.delete_account_text') }}</p>
    <form method="post" action="{{ route('account.destroy') }}" class="formulaire"
        data-confirm="{{ __('interface.confirm_delete_account') }}" onsubmit="return confirm(this.dataset.confirm)">
        @csrf
        @method('DELETE')
        <x-champ name="delete_password" type="password" :label="__('interface.confirm_with_password')" required autocomplete="current-password" />
        <div class="actions">
            <button type="submit" class="bouton bouton-danger"><x-icon name="trash-2" :size="18" /> {{ __('interface.delete_account') }}</button>
        </div>
    </form>
</section>
@endsection
