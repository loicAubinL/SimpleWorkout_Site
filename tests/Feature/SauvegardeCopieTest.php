<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SauvegardeCopieTest extends TestCase
{
    use RefreshDatabase;

    public function test_sauvegarder_et_retirer_un_workout_public(): void
    {
        [$split] = $this->creerSplit(User::factory()->create(), ['name' => 'Upper Lower', 'is_public' => true]);
        $user = User::factory()->create();

        $this->actingAs($user)->post("/workout-splits/{$split->id}/sauvegarde")->assertRedirect();
        $this->actingAs($user)->post("/workout-splits/{$split->id}/sauvegarde"); // pas de doublon
        $this->assertSame(1, $user->savedWorkouts()->count());
        $this->actingAs($user)->get('/sauvegardes')->assertSee('Upper Lower');

        $this->actingAs($user)->delete("/workout-splits/{$split->id}/sauvegarde")->assertRedirect();
        $this->assertSame(0, $user->savedWorkouts()->count());
    }

    public function test_on_peut_sauvegarder_son_propre_workout_public(): void
    {
        $user = User::factory()->create();
        [$split] = $this->creerSplit($user, ['is_public' => true]);

        $this->actingAs($user)->post("/workout-splits/{$split->id}/sauvegarde")->assertRedirect();
        $this->assertSame(1, $user->savedWorkouts()->count());
    }

    public function test_impossible_de_sauvegarder_ou_copier_un_workout_prive_d_un_autre(): void
    {
        [$split] = $this->creerSplit(User::factory()->create());
        $user = User::factory()->create();

        $this->actingAs($user)->post("/workout-splits/{$split->id}/sauvegarde")->assertForbidden();
        $this->actingAs($user)->post("/workout-splits/{$split->id}/copier")->assertForbidden();
    }

    public function test_un_workout_redevenu_prive_disparait_des_sauvegardes(): void
    {
        [$split] = $this->creerSplit(User::factory()->create(), ['name' => 'Bientôt privé', 'is_public' => true]);
        $user = User::factory()->create();
        $user->savedWorkouts()->attach($split);

        $split->update(['is_public' => false]);

        $this->actingAs($user)->get('/sauvegardes')->assertDontSee('Bientôt privé');
    }

    public function test_copier_cree_une_copie_profonde_privee_et_modifiable(): void
    {
        $auteur = User::factory()->create();
        [$original] = $this->creerSplit($auteur, ['name' => 'PPL', 'is_public' => true, 'is_active' => true]);
        $user = User::factory()->create();

        $reponse = $this->actingAs($user)->post("/workout-splits/{$original->id}/copier");

        $copie = WorkoutSplit::where('user_id', $user->id)->firstOrFail();
        $reponse->assertRedirect("/workout-splits/{$copie->id}");
        $this->assertSame('PPL (copie)', $copie->name);
        $this->assertFalse($copie->is_public);
        $this->assertFalse($copie->is_active);
        $this->assertSame(1, $copie->workoutDays()->count());
        $this->assertSame('Développé couché', $copie->exercises()->first()->name);
        $this->assertSame($user->id, $copie->workoutDays()->first()->user_id);

        // La copie appartient à l'utilisateur : il peut la modifier.
        $this->actingAs($user)->get("/workout-splits/{$copie->id}/edit")->assertOk();
        // L'original n'est pas touché.
        $this->assertSame(1, $original->workoutDays()->count());
    }
}
