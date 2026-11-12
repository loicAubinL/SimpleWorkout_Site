<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CompteController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->loadCount(['workoutSplits', 'savedWorkouts', 'loggedSets']);

        return view('account.show', ['user' => $user]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $donnees = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($donnees);

        return redirect()->route('account.show')->with('status', __('interface.profile_updated'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update(['password' => $donnees['password']]);
        $request->session()->regenerate();

        return redirect()->route('account.show')->with('status', __('interface.password_updated'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->isManager() && User::where('role', User::ROLE_MANAGER)->count() <= 1) {
            throw ValidationException::withMessages([
                'delete_password' => __('interface.last_manager'),
            ]);
        }

        Auth::logout();
        $user->delete(); // Les workouts, jours, exercices et séries suivent (cascade).

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('accueil')->with('status', __('interface.account_deleted'));
    }
}
