<?php

use App\Http\Controllers\AdministrationController;
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
Route::get('/', function (Request $request) {
    $actif = $request->user()?->workoutSplits()
        ->where('is_active', true)
        ->withCount('workoutDays')
        ->first();

    $derniers = WorkoutSplit::public()
        ->with('user:id,name')
        ->withCount('workoutDays')
        ->latest()
        ->limit(6)
        ->get();

    return view('accueil', compact('actif', 'derniers'));
})->name('accueil');

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

    Route::get('/recherche/workouts', RechercheWorkoutSplitController::class)->name('recherche.workouts');

    Route::get('/compte', [CompteController::class, 'show'])->name('account.show');
});

/*
|--------------------------------------------------------------------------
| Administration (responsable)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureManager::class])->group(function () {
    Route::get('/administration', [AdministrationController::class, 'index'])
        ->name('admin.index');
});
