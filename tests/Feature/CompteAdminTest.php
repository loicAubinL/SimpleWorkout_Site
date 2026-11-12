<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompteAdminTest extends TestCase
{
    use RefreshDatabase;

    // ---------- Mon compte ----------

    public function test_modifier_son_profil(): void
    {
        $user = User::factory()->create();
        $autre = User::factory()->create();

        $this->actingAs($user)->patch('/compte', ['name' => 'Nom', 'email' => $autre->email])
            ->assertSessionHasErrors('email');

        $this->actingAs($user)->patch('/compte', ['name' => 'Nouveau nom', 'email' => 'neuf@example.test'])
            ->assertRedirect('/compte');
        $this->assertSame('neuf@example.test', $user->fresh()->email);
    }

    public function test_changer_son_mot_de_passe_exige_l_actuel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/compte/mot-de-passe', [
            'current_password' => 'mauvais', 'password' => 'nouveaumdp1', 'password_confirmation' => 'nouveaumdp1',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($user)->put('/compte/mot-de-passe', [
            'current_password' => 'password', 'password' => 'nouveaumdp1', 'password_confirmation' => 'nouveaumdp1',
        ])->assertRedirect('/compte');
        $this->assertTrue(Hash::check('nouveaumdp1', $user->fresh()->password));
    }

    public function test_supprimer_son_compte_supprime_ses_donnees(): void
    {
        $user = User::factory()->create();
        [$split, , $exercice] = $this->creerSplit($user);
        $user->loggedSets()->create(['exercise_id' => $exercice->id, 'set_number' => 1, 'reps' => 5]);

        $this->actingAs($user)->delete('/compte', ['delete_password' => 'mauvais'])->assertSessionHasErrors('delete_password');
        $this->assertModelExists($user);

        $this->actingAs($user)->delete('/compte', ['delete_password' => 'password'])->assertRedirect('/');
        $this->assertGuest();
        $this->assertModelMissing($user);
        $this->assertModelMissing($split);
        $this->assertDatabaseCount('logged_sets', 0);
    }

    public function test_le_dernier_responsable_ne_peut_pas_supprimer_son_compte(): void
    {
        $responsable = $this->responsable();

        $this->actingAs($responsable)->delete('/compte', ['delete_password' => 'password'])
            ->assertSessionHasErrors('delete_password');
        $this->assertModelExists($responsable);
    }

    // ---------- Administration ----------

    public function test_un_membre_n_a_pas_acces_a_l_administration(): void
    {
        $membre = User::factory()->create();

        $this->actingAs($membre)->get('/administration/comptes')->assertForbidden();
        $this->actingAs($membre)->post('/administration/comptes', [])->assertForbidden();
    }

    public function test_le_responsable_cree_modifie_et_supprime_des_comptes(): void
    {
        $responsable = $this->responsable();

        $this->actingAs($responsable)->get('/administration/comptes')->assertOk();

        $this->actingAs($responsable)->post('/administration/comptes', [
            'name' => 'Coach', 'email' => 'coach@example.test', 'role' => 'manager',
            'password' => 'motdepasse1', 'password_confirmation' => 'motdepasse1',
        ])->assertRedirect();
        $coach = User::where('email', 'coach@example.test')->firstOrFail();
        $this->assertTrue($coach->isManager());

        $this->actingAs($responsable)->patch("/administration/comptes/{$coach->id}", [
            'name' => 'Coach', 'email' => 'coach@example.test', 'role' => 'member',
        ])->assertRedirect();
        $this->assertFalse($coach->fresh()->isManager());
        $this->assertTrue(Hash::check('motdepasse1', $coach->fresh()->password)); // mot de passe conservé

        $this->actingAs($responsable)->delete("/administration/comptes/{$coach->id}")->assertRedirect('/administration/comptes');
        $this->assertModelMissing($coach);
    }

    public function test_garde_fous_du_responsable(): void
    {
        $responsable = $this->responsable();

        $this->actingAs($responsable)->delete("/administration/comptes/{$responsable->id}")->assertSessionHasErrors('compte');
        $this->assertModelExists($responsable);

        $this->actingAs($responsable)->patch("/administration/comptes/{$responsable->id}", [
            'name' => 'R', 'email' => $responsable->email, 'role' => 'member',
        ])->assertSessionHasErrors('role');
        $this->assertTrue($responsable->fresh()->isManager());
    }

    public function test_le_responsable_gere_les_workouts_de_tous(): void
    {
        $responsable = $this->responsable();
        $membre = User::factory()->create();
        [$prive, $jour] = $this->creerSplit($membre, ['name' => 'Privé du membre']);

        $this->actingAs($responsable)->get("/administration/comptes/{$membre->id}")->assertOk()->assertSee('Privé du membre');
        $this->actingAs($responsable)->get("/workout-splits/{$prive->id}")->assertOk();
        $this->actingAs($responsable)->patch("/workout-splits/{$prive->id}", ['name' => 'Corrigé'])->assertRedirect();
        $this->actingAs($responsable)->post("/days/{$jour->id}/exercises", ['name' => 'Gainage', 'target_sets' => 3, 'target_reps' => '30 s'])->assertRedirect();
        $this->assertSame(2, $jour->exercises()->count());

        // Créer un workout pour ce membre
        $this->actingAs($responsable)->post("/administration/comptes/{$membre->id}/workouts", ['name' => 'Programme du coach'])->assertRedirect();
        $this->assertSame(1, WorkoutSplit::where('user_id', $membre->id)->where('name', 'Programme du coach')->count());

        $this->actingAs($responsable)->delete("/workout-splits/{$prive->id}")->assertRedirect("/administration/comptes/{$membre->id}");
        $this->assertModelMissing($prive);
    }
}
