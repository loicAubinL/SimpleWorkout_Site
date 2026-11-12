<?php

namespace Tests\Feature;

use App\Models\LoggedSet;
use App\Models\User;
use App\Models\WorkoutDay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_accueil_affiche_le_workout_du_jour(): void
    {
        $user = User::factory()->create();
        [$split] = $this->creerSplit($user, ['is_active' => true]);

        $this->actingAs($user)->get('/')->assertOk()->assertSee('Push')->assertSee('Développé couché');
    }

    public function test_enregistrer_et_supprimer_une_serie(): void
    {
        $user = User::factory()->create();
        [$split, , $exercice] = $this->creerSplit($user, ['is_active' => true]);

        $this->actingAs($user)->get("/seance/{$split->id}")->assertOk()->assertSee('Développé couché');

        $this->actingAs($user)->post("/exercises/{$exercice->id}/series", ['weight' => '62.5', 'reps' => 8])->assertRedirect();
        $this->actingAs($user)->post("/exercises/{$exercice->id}/series", ['weight' => '62.5', 'reps' => 7]);

        $series = LoggedSet::orderBy('set_number')->get();
        $this->assertSame([1, 2], $series->pluck('set_number')->all());
        $this->assertSame($user->id, $series[0]->user_id);

        $this->actingAs($user)->delete("/series/{$series[0]->id}")->assertRedirect();
        $this->assertModelMissing($series[0]);
    }

    public function test_validation_d_une_serie(): void
    {
        $user = User::factory()->create();
        [, , $exercice] = $this->creerSplit($user);

        $this->actingAs($user)->post("/exercises/{$exercice->id}/series", ['weight' => -1, 'reps' => 0])
            ->assertSessionHasErrors(['weight', 'reps']);
        $this->assertDatabaseCount('logged_sets', 0);
    }

    public function test_terminer_la_seance_fait_avancer_le_cycle(): void
    {
        $user = User::factory()->create();
        [$split, $push] = $this->creerSplit($user, ['is_active' => true]);
        $pull = WorkoutDay::factory()->create(['user_id' => $user->id, 'workout_split_id' => $split->id, 'name' => 'Pull', 'position' => 1]);

        $this->assertTrue($split->currentDay()->is($push));

        $this->actingAs($user)->post("/seance/{$split->id}/terminer", ['jour' => $push->id])->assertRedirect('/historique');
        $this->assertTrue($split->fresh()->currentDay()->is($pull));

        $this->actingAs($user)->post("/seance/{$split->id}/terminer", ['jour' => $pull->id]);
        $this->assertTrue($split->fresh()->currentDay()->is($push));
    }

    public function test_impossible_de_faire_une_seance_ou_noter_sur_le_workout_d_un_autre(): void
    {
        [$split, , $exercice] = $this->creerSplit(User::factory()->create(), ['is_public' => true]);
        $intrus = User::factory()->create();

        $this->actingAs($intrus)->get("/seance/{$split->id}")->assertForbidden();
        $this->actingAs($intrus)->post("/exercises/{$exercice->id}/series", ['reps' => 5])->assertForbidden();
        $this->actingAs($intrus)->get("/progression/{$exercice->id}")->assertForbidden();
    }

    public function test_on_ne_peut_pas_supprimer_la_serie_d_un_autre(): void
    {
        $user = User::factory()->create();
        [, , $exercice] = $this->creerSplit($user);
        $serie = $user->loggedSets()->create(['exercise_id' => $exercice->id, 'set_number' => 1, 'reps' => 5]);

        $this->actingAs(User::factory()->create())->delete("/series/{$serie->id}")->assertForbidden();
        $this->assertModelExists($serie);
    }

    public function test_historique_et_progression(): void
    {
        $user = User::factory()->create();
        [, , $exercice] = $this->creerSplit($user);
        $user->loggedSets()->create(['exercise_id' => $exercice->id, 'set_number' => 1, 'weight' => 60, 'reps' => 8, 'logged_at' => now()->subWeek()]);
        $user->loggedSets()->create(['exercise_id' => $exercice->id, 'set_number' => 1, 'weight' => 65, 'reps' => 8, 'logged_at' => now()]);

        $this->actingAs($user)->get('/historique')->assertOk()->assertSee('Développé couché')->assertSee('65 kg');
        $this->actingAs($user)->get("/progression/{$exercice->id}")->assertOk()->assertSee('+5 kg')->assertSee('<svg class="graphique"', false);
    }
}
