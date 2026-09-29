<?php

declare(strict_types=1);

class ControleurUtilisateur
{
    private $utilisateurs;
    private $authentification;
    private $vue;

    public function __construct(Utilisateur $utilisateurs, Authentification $authentification, Vue $vue)
    {
        $this->utilisateurs = $utilisateurs;
        $this->authentification = $authentification;
        $this->vue = $vue;
    }

    public function connexion(?string $erreur = null, string $email = ''): void
    {
        if ($this->authentification->utilisateur() !== null) {
            rediriger();
        }

        $this->vue->afficher('utilisateurs/connexion', compact('erreur', 'email'), 'Connexion');
    }

    public function authentifier(array $donnees): void
    {
        $email = trim((string) ($donnees['email'] ?? ''));
        $motDePasse = (string) ($donnees['mot_de_passe'] ?? '');
        $utilisateur = $this->utilisateurs->trouverParEmail($email);

        // Même message si le compte n'existe pas ou si le mot de passe est faux
        if ($utilisateur === null || !password_verify($motDePasse, $utilisateur['password_hash'])) {
            $this->connexion('Identifiant ou mot de passe invalide.', $email);
            return;
        }

        $this->authentification->connecter($utilisateur);
        rediriger();
    }

    public function inscription(array $erreurs = [], array $valeurs = ['nom' => '', 'email' => '']): void
    {
        if ($this->authentification->utilisateur() !== null) {
            rediriger();
        }

        $this->vue->afficher('utilisateurs/inscription', compact('erreurs', 'valeurs'), 'Créer un compte');
    }

    public function creerCompte(array $donnees): void
    {
        $valeurs = [
            'nom' => trim((string) ($donnees['nom'] ?? '')),
            'email' => trim((string) ($donnees['email'] ?? '')),
        ];
        $motDePasse = (string) ($donnees['mot_de_passe'] ?? '');
        $confirmation = (string) ($donnees['confirmation'] ?? '');
        $erreurs = [];

        if ($valeurs['nom'] === '' || mb_strlen($valeurs['nom']) > 100) {
            $erreurs['nom'] = 'Le nom est obligatoire (100 caractères maximum).';
        }

        if (filter_var($valeurs['email'], FILTER_VALIDATE_EMAIL) === false) {
            $erreurs['email'] = 'Adresse courriel invalide.';
        } elseif ($this->utilisateurs->trouverParEmail($valeurs['email']) !== null) {
            $erreurs['email'] = 'Cette adresse est déjà utilisée.';
        }

        if (mb_strlen($motDePasse) < 8) {
            $erreurs['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($motDePasse !== $confirmation) {
            $erreurs['confirmation'] = 'La confirmation ne correspond pas.';
        }

        if ($erreurs !== []) {
            http_response_code(422);
            $this->inscription($erreurs, $valeurs);
            return;
        }


        $this->utilisateurs->creer($valeurs['nom'], $valeurs['email'], password_hash($motDePasse, PASSWORD_DEFAULT));
        rediriger('connexion');
    }

    public function deconnecter(): void
    {
        $this->authentification->deconnecter();
        rediriger();
    }
}
