<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutSplitController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\WorkoutPublicController;
use App\Models\WorkoutSplit;
use App\Http\Controllers\RechercheWorkoutSplitController;


Route::get('/inscription', [InscriptionController::class, 'create'])
    ->middleware('guest')->name('register');
Route::post('/inscription', [InscriptionController::class, 'store'])
    ->middleware('guest')->name('register.store');

Route::get('/connexion', [AuthController::class, 'create'])
    ->middleware('guest')->name('login');
Route::post('/connexion', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:6,1'])->name('login.store');
Route::post('/deconnexion', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::post('/langue', function (Request $request) {
    $data = $request->validate([
        'locale' => ['required', Rule::in(['fr', 'en'])],
    ]);

    $request->session()->put('locale', $data['locale']);

    return back();
})->name('locale.update');

Route::get('/', function (Request $request) {
    $actif = $request->user()?->workoutSplits()
        ->where('is_active', true)
        ->withCount('workoutDays')
        ->first();

    $derniers = WorkoutSplit::with('user:id,name')
        ->withCount('workoutDays')
        ->latest()
        ->limit(5)
        ->get();

    return view('accueil', compact('actif', 'derniers'));
})->name('accueil');

Route::get('/workouts-publics', WorkoutPublicController::class)->name('workouts.publics');

Route::get('/compte', [CompteController::class, 'show'])
    ->middleware('auth')->name('account.show');

Route::get('/recherche/workouts', RechercheWorkoutSplitController::class)
    ->middleware('auth')->name('recherche.workouts');

Route::resource('workout-splits', WorkoutSplitController::class)
    ->middlewareFor(['index', 'create', 'store', 'edit', 'update', 'destroy'], 'auth');

Route::view('/recits', 'recits')->name('recits');

Route::delete('/workout-splits/{workoutSplit}/programme', [WorkoutSplitController::class, 'destroyProgram'])
    ->middleware('auth')->name('workout-splits.program.destroy');
