@php
    $liens = [
        ['route' => 'accueil', 'actif' => 'accueil', 'icone' => 'house', 'texte' => __('interface.nav_home')],
        ['route' => 'workouts.publics', 'actif' => 'workouts.publics', 'icone' => 'globe', 'texte' => __('interface.public_workouts')],
    ];

    if (auth()->check()) {
        $liens[] = ['route' => 'workout-splits.index', 'actif' => 'workout-splits.*|days.*|exercises.*', 'icone' => 'dumbbell', 'texte' => __('interface.nav_workouts')];
        $liens[] = ['route' => 'saved.index', 'actif' => 'saved.*', 'icone' => 'bookmark', 'texte' => __('interface.nav_saved')];
        $liens[] = ['route' => 'history.index', 'actif' => 'history.*|progress.*|session.*', 'icone' => 'history', 'texte' => __('interface.nav_history')];

        if (auth()->user()->isManager()) {
            $liens[] = ['route' => 'admin.users.index', 'actif' => 'admin.*', 'icone' => 'shield', 'texte' => __('interface.admin')];
        }
    }
@endphp
@foreach (array_filter($liens, fn ($l) => Route::has($l['route'])) as $lien)
@php $estActif = request()->routeIs(...explode('|', $lien['actif'])); @endphp
<a href="{{ route($lien['route']) }}" @class(['nav-lien', 'actif' => $estActif]) @if ($estActif) aria-current="page" @endif>
    <x-icon :name="$lien['icone']" :size="18" />
    {{ $lien['texte'] }}
</a>
@endforeach
