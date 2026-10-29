<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutSplitController;

use App\Models\User;

Route::get('/', function () {
    $actif = User::query()->orderBy('id')->first()
        ?->workoutSplits()
        ->where('is_active', true)
        ->withCount('workoutDays')
        ->first();

    return view('accueil', compact('actif'));
})->name('accueil');

Route::resource('workout-splits', WorkoutSplitController::class);

Route::view('/recits', 'recits')->name('recits');

Route::delete('/workout-splits/{workoutSplit}/programme', [WorkoutSplitController::class, 'destroyProgram'])
    ->name('workout-splits.program.destroy');
