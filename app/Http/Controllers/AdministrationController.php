<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class AdministrationController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->withCount('workoutSplits')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.index', compact('users'));
    }
}
