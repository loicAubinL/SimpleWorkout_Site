<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutSplitController;

Route::redirect('/', '/workout-splits');

Route::resource('workout-splits', WorkoutSplitController::class);

Route::view('/recits', 'recits')->name('recits');
