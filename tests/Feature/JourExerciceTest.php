<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JourExerciceTest extends TestCase
{
    use RefreshDatabase;

    public function test_ajouter_modifier_supprimer_un_jour(): void
    {
        $user = User::factory()->create();
        [$split, $jour] = $this->creerSplit($user);

        $this->actingAs($user)->post("/workout-splits/{$split->id}/days", ['name' => 'Pull'])->assertRedirect();
        $nouveau = $split->workoutDays()->where('name', 'Pull')->firstOrFail();
        $this->assertSame(1, $nouveau->position);
        $this->assertSame($user->id, $nouveau->user_id);

        $this->actingAs($user)->patch("/days/{$nouveau->id}", ['name' => 'Tirage', 'is_rest_day' => '1'])->assertRedirect();
        $this->assertTrue($nouveau->fresh()->is_rest_day);

        $this->actingAs($user)->patch("/days/{$nouveau->id}/deplacer", ['direction' => 'up'])->assertRedirect();
        $this->assertSame(['Tirage', 'Push'], $split->workoutDays()->pluck('name')->all());

        $this->actingAs($user)->delete("/days/{$jour->id}")->assertRedirect();
        $this->assertModelMissing($jour);
    }

    public function test_ajouter_modifier_supprimer_un_exercice(): void
    {
        $user = User::factory()->create();
        [, $jour, $exercice] = $this->creerSplit($user);

        $this->actingAs($user)->post("/days/{$jour->id}/exercises", [
            'name' => 'Squat',
            'target_sets' => 4,
            'target_reps' => '5',
            'weight' => '100',
            'rest_seconds' => 180,
        ])->assertRedirect();

        $squat = $jour->exercises()->where('name', 'Squat')->firstOrFail();
        $this->assertSame('100.00', $squat->weight);
        $this->assertSame(180, $squat->rest_seconds);

        $this->actingAs($user)->patch("/exercises/{$squat->id}", [
            'name' => 'Squat avant', 'target_sets' => 3, 'target_reps' => '8-10', 'weight' => '70.5',
        ])->assertRedirect();
        $this->assertSame('Squat avant', $squat->fresh()->name);

        $this->actingAs($user)->delete("/exercises/{$exercice->id}")->assertRedirect();
        $this->assertModelMissing($exercice);
    }

    public function test_validation_d_un_exercice(): void
    {
        $user = User::factory()->create();
        [, $jour] = $this->creerSplit($user);

        $this->actingAs($user)->post("/days/{$jour->id}/exercises", [
            'name' => '', 'target_sets' => 0, 'target_reps' => '', 'weight' => -5, 'rest_seconds' => 5000,
        ])->assertSessionHasErrors(['name', 'target_sets', 'target_reps', 'weight', 'rest_seconds']);
    }

    public function test_un_autre_membre_ne_peut_pas_toucher_aux_jours_et_exercices(): void
    {
        [$split, $jour, $exercice] = $this->creerSplit(User::factory()->create(), ['is_public' => true]);
        $intrus = User::factory()->create();

        $this->actingAs($intrus)->post("/workout-splits/{$split->id}/days", ['name' => 'X'])->assertForbidden();
        $this->actingAs($intrus)->patch("/days/{$jour->id}", ['name' => 'X'])->assertForbidden();
        $this->actingAs($intrus)->delete("/days/{$jour->id}")->assertForbidden();
        $this->actingAs($intrus)->post("/days/{$jour->id}/exercises", ['name' => 'X', 'target_sets' => 3, 'target_reps' => '5'])->assertForbidden();
        $this->actingAs($intrus)->delete("/exercises/{$exercice->id}")->assertForbidden();

        $this->assertModelExists($jour);
        $this->assertModelExists($exercice);
    }
}
