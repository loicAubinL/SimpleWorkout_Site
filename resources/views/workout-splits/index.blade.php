<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Mes workouts</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <h1>Mes workouts</h1>

    @forelse ($splits as $split)
    <article>
        <h2><a href="{{ route('workout-splits.show', $split) }}">{{ $split->name }}</a></h2>
        <p>{{ $split->workout_days_count }} jour(s){{ $split->is_active ? ' · Actif' : '' }}</p>
    </article>
    @empty
    <p>Aucun workout pour l'instant.</p>
    @endforelse

    {{ $splits->links() }}
</body>

</html>