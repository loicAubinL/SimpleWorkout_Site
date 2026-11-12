<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_crud_complet_de_ses_propres_workouts(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/workout-splits', ['name' => 'PPL', 'is_public' => '1'])
            ->assertRedirect();
        $split = WorkoutSplit::firstOrFail();
        $this->assertTrue($split->is_public);
        $this->assertSame($user->id, $split->user_id);

        $this->actingAs($user)->get("/workout-splits/{$split->id}")->assertOk()->assertSee('PPL');

        $this->actingAs($user)
            ->patch("/workout-splits/{$split->id}", ['name' => 'Push Pull Legs'])
            ->assertRedirect("/workout-splits/{$split->id}");
        $this->assertSame('Push Pull Legs', $split->fresh()->name);
        $this->assertFalse($split->fresh()->is_public);

        $this->actingAs($user)->delete("/workout-splits/{$split->id}")->assertRedirect('/workout-splits');
        $this->assertModelMissing($split);
    }

    public function test_validation_du_nom(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/workout-splits', ['name' => ''])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('workout_splits', 0);
    }

    public function test_un_seul_workout_actif(): void
    {
        $user = User::factory()->create();
        [$a] = $this->creerSplit($user, ['is_active' => true]);
        [$b] = $this->creerSplit($user);

        $this->actingAs($user)->patch("/workout-splits/{$b->id}/activer")->assertRedirect();

        $this->assertFalse($a->fresh()->is_active);
        $this->assertTrue($b->fresh()->is_active);
    }

    public function test_un_autre_membre_ne_peut_ni_modifier_ni_supprimer(): void
    {
        [$split] = $this->creerSplit(User::factory()->create(), ['is_public' => true]);
        $intrus = User::factory()->create();

        $this->actingAs($intrus)->get("/workout-splits/{$split->id}/edit")->assertForbidden();
        $this->actingAs($intrus)->patch("/workout-splits/{$split->id}", ['name' => 'X'])->assertForbidden();
        $this->actingAs($intrus)->delete("/workout-splits/{$split->id}")->assertForbidden();
        $this->assertModelExists($split);
    }

    public function test_un_workout_prive_est_invisible_pour_les_autres(): void
    {
        $proprietaire = User::factory()->create();
        [$prive] = $this->creerSplit($proprietaire);

        $this->get("/workout-splits/{$prive->id}")->assertForbidden();
        $this->actingAs(User::factory()->create())->get("/workout-splits/{$prive->id}")->assertForbidden();
        $this->actingAs($proprietaire)->get("/workout-splits/{$prive->id}")->assertOk();
    }

    public function test_page_publique_ne_montre_que_les_workouts_publics_et_recherche(): void
    {
        $user = User::factory()->create(['name' => 'Alex']);
        $this->creerSplit($user, ['name' => 'Upper Lower', 'is_public' => true]);
        $this->creerSplit($user, ['name' => 'Bras express', 'is_public' => true]);
        $this->creerSplit($user, ['name' => 'Secret', 'is_public' => false]);

        $this->get('/workouts-publics')
            ->assertOk()
            ->assertSee('Upper Lower')
            ->assertSee('Bras express')
            ->assertDontSee('Secret');

        $this->get('/workouts-publics?q=bras')
            ->assertSee('Bras express')
            ->assertDontSee('Upper Lower');
    }

    public function test_les_valeurs_sont_echappees(): void
    {
        $user = User::factory()->create();
        [$split] = $this->creerSplit($user, ['name' => '<script>alert(1)</script>']);

        $this->actingAs($user)->get("/workout-splits/{$split->id}")
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_recherche_json_de_mes_workouts(): void
    {
        $user = User::factory()->create();
        $this->creerSplit($user, ['name' => 'Mon PPL']);
        $this->creerSplit(User::factory()->create(), ['name' => 'PPL autre', 'is_public' => true]);

        $this->actingAs($user)->getJson('/recherche/workouts?q=PPL')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', 'Mon PPL');
    }
}
