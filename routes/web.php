<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutSplitController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\AuthController; 

use App\Models\User;
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

Route::get('/', function () {
    $actif = User::query()->orderBy('id')->first()
        ?->workoutSplits()
        ->where('is_active', true)
        ->withCount('workoutDays')
        ->first();

    return view('accueil', compact('actif'));
})->name('accueil');

Route::get('/recherche/workouts', RechercheWorkoutSplitController::class)
    ->name('recherche.workouts');

Route::resource('workout-splits', WorkoutSplitController::class);

Route::view('/recits', 'recits')->name('recits');

Route::delete('/workout-splits/{workoutSplit}/programme', [WorkoutSplitController::class, 'destroyProgram'])
    ->name('workout-splits.program.destroy');
