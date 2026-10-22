<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>{{ $split->name }}</title>
</head>

<body>
    <p><a href="{{ route('workout-splits.index') }}">← Mes workouts</a></p>

    <h1>{{ $split->name }}</h1>
    <p>{{ $split->is_active ? 'Actif' : 'Inactif' }}</p>

    <h2>Jours</h2>
    @forelse ($split->workoutDays as $day)
    <article>
        <h3>{{ $day->name }}</h3>
        <p>{{ $day->is_rest_day ? 'Jour de repos' : 'Entraînement' }}</p>
    </article>
    @empty
    <p>Aucun jour dans ce workout.</p>
    @endforelse
</body>

</html>