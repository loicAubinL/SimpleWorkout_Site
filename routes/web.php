<?php

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\CopieWorkoutSplitController;
use App\Http\Controllers\SavedWorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\RechercheWorkoutSplitController;
use App\Http\Controllers\WorkoutDayController;
use App\Http\Controllers\WorkoutPublicController;
use App\Http\Controllers\WorkoutSplitController;
use App\Http\Middleware\EnsureManager;
use App\Models\WorkoutSplit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| Pages publiques
|--------------------------------------------------------------------------
*/
Route::get('/', AccueilController::class)->name('accueil');

Route::get('/workouts-publics', WorkoutPublicController::class)->name('workouts.publics');
Route::view('/recits', 'recits')->name('recits');

Route::post('/langue', function (Request $request) {
    $data = $request->validate([
        'locale' => ['required', Rule::in(['fr', 'en'])],
    ]);

    $request->session()->put('locale', $data['locale']);

    return back();
})->name('locale.update');

/*
|--------------------------------------------------------------------------
| Inscription et connexion
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [InscriptionController::class, 'create'])->name('register');
    Route::post('/inscription', [InscriptionController::class, 'store'])->name('register.store');

    Route::get('/connexion', [AuthController::class, 'create'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])
        ->middleware('throttle:6,1')->name('login.store');
});

Route::post('/deconnexion', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Workouts (split → jours → exercices)
|--------------------------------------------------------------------------
| Le détail d'un workout est public si le workout l'est (policy « view »).
*/
Route::resource('workout-splits', WorkoutSplitController::class)
    ->middlewareFor(['index', 'create', 'store', 'edit', 'update', 'destroy'], 'auth');

Route::middleware('auth')->group(function () {
    Route::patch('/workout-splits/{workoutSplit}/activer', [WorkoutSplitController::class, 'activate'])
        ->name('workout-splits.activate');

    Route::resource('workout-splits.days', WorkoutDayController::class)
        ->shallow()
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('/days/{day}/deplacer', [WorkoutDayController::class, 'move'])->name('days.move');

    Route::resource('days.exercises', ExerciseController::class)
        ->shallow()
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('/exercises/{exercise}/deplacer', [ExerciseController::class, 'move'])->name('exercises.move');

    Route::post('/workout-splits/{workoutSplit}/copier', CopieWorkoutSplitController::class)
        ->name('workout-splits.copy');

    Route::get('/sauvegardes', [SavedWorkoutController::class, 'index'])->name('saved.index');
    Route::post('/workout-splits/{workoutSplit}/sauvegarde', [SavedWorkoutController::class, 'store'])->name('saved.store');
    Route::delete('/workout-splits/{workoutSplit}/sauvegarde', [SavedWorkoutController::class, 'destroy'])->name('saved.destroy');

    Route::get('/seance/{workoutSplit}', [SeanceController::class, 'show'])->name('session.show');
    Route::post('/seance/{workoutSplit}/terminer', [SeanceController::class, 'finish'])->name('session.finish');
    Route::post('/exercises/{exercise}/series', [SeanceController::class, 'storeSet'])->name('session.sets.store');
    Route::delete('/series/{loggedSet}', [SeanceController::class, 'destroySet'])->name('session.sets.destroy');

    Route::get('/historique', [HistoriqueController::class, 'index'])->name('history.index');
    Route::get('/progression/{exercise}', [HistoriqueController::class, 'progress'])->name('progress.show');

    Route::get('/recherche/workouts', RechercheWorkoutSplitController::class)->name('recherche.workouts');

    Route::get('/compte', [CompteController::class, 'show'])->name('account.show');
    Route::patch('/compte', [CompteController::class, 'update'])->name('account.update');
    Route::put('/compte/mot-de-passe', [CompteController::class, 'updatePassword'])->name('account.password');
    Route::delete('/compte', [CompteController::class, 'destroy'])->name('account.destroy');
});

/*
|--------------------------------------------------------------------------
| Administration (responsable)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureManager::class])
    ->prefix('administration')
    ->name('admin.')
    ->group(function () {
        Route::redirect('/', '/administration/comptes')->name('index');

        Route::resource('comptes', UserController::class)
            ->parameters(['comptes' => 'user'])
            ->names('users');

        Route::get('/comptes/{user}/workouts/create', [UserController::class, 'createSplit'])->name('users.splits.create');
        Route::post('/comptes/{user}/workouts', [UserController::class, 'storeSplit'])->name('users.splits.store');
    });
