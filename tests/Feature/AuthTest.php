<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function donneesInscription(array $autres = []): array
    {
        return [
            'name' => 'Nouvelle personne',
            'email' => 'nouveau@example.test',
            'email_confirmation' => 'nouveau@example.test',
            'password' => 'motdepasse1',
            'password_confirmation' => 'motdepasse1',
            'g-recaptcha-response' => 'jeton',
            ...$autres,
        ];
    }

    public function test_inscription_avec_recaptcha_valide_cree_un_membre_connecte(): void
    {
        Http::fake(['*recaptcha*' => Http::response(['success' => true])]);

        $this->post('/inscription', $this->donneesInscription())->assertRedirect('/');

        $user = User::where('email', 'nouveau@example.test')->firstOrFail();
        $this->assertSame(User::ROLE_MEMBER, $user->role);
        $this->assertAuthenticatedAs($user);
    }

    public function test_inscription_refusee_si_recaptcha_echoue(): void
    {
        Http::fake(['*recaptcha*' => Http::response(['success' => false])]);

        $this->post('/inscription', $this->donneesInscription())
            ->assertSessionHasErrors('g-recaptcha-response');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'nouveau@example.test']);
    }

    public function test_inscription_ne_peut_pas_choisir_son_role(): void
    {
        Http::fake(['*recaptcha*' => Http::response(['success' => true])]);

        $this->post('/inscription', $this->donneesInscription(['role' => User::ROLE_MANAGER]));

        $this->assertSame(User::ROLE_MEMBER, User::where('email', 'nouveau@example.test')->value('role'));
    }

    public function test_connexion_et_deconnexion(): void
    {
        $user = User::factory()->create();

        $this->post('/connexion', ['email' => $user->email, 'password' => 'mauvais'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/connexion', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $this->post('/deconnexion')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_pages_protegees_redirigent_le_visiteur(): void
    {
        foreach (['/workout-splits', '/sauvegardes', '/historique', '/compte', '/administration/comptes'] as $url) {
            $this->get($url)->assertRedirect('/connexion');
        }
    }
}
