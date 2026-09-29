<?php

declare(strict_types=1);

class Routeur
{
    private $accueil;
    private $recits;
    private $workouts;
    private $exercices;
    private $loggedSets;
    private $utilisateurs;
    private $erreurs;

    public function __construct(
        ControleurAccueil $accueil,
        ControleurRecits $recits,
        ControleurWorkout $workouts,
        ControleurExercice $exercices,
        ControleurLoggedSet $loggedSets,
        ControleurUtilisateur $utilisateurs,
        ControleurErreur $erreurs
    ) {
        $this->accueil = $accueil;
        $this->recits = $recits;
        $this->workouts = $workouts;
        $this->exercices = $exercices;
        $this->loggedSets = $loggedSets;
        $this->utilisateurs = $utilisateurs;
        $this->erreurs = $erreurs;
    }

    public function router(): void
    {
        $action = $_GET['action'] ?? 'accueil';

        switch ($action) {
            // --- Lecture ---
            case 'accueil':
                $this->accueil->index();
                break;

            case 'recits':
                $this->recits->index();
                break;

            case 'workouts':
                $this->workouts->liste();
                break;

            case 'workout':
                $this->workouts->detail($this->lireIdGet());
                break;

            case 'jour':
                $this->workouts->jour($this->lireIdGet());
                break;

            case 'exercice':
                $this->loggedSets->liste($this->lireIdGet());
                break;

            // --- Récit : modifier un exercice ---
            case 'exercice-modifier':
                $this->exercices->formulaire($this->lireIdGet());
                break;

            case 'exercice-enregistrer':
                $this->exigerEcriture($_POST['csrf_token'] ?? null);
                $this->exercices->enregistrer($this->lireIdPost(), $_POST);
                break;

            // --- Séries ---
            case 'logged-set-ajouter':
                $this->exigerEcriture($_POST['csrf_token'] ?? null);
                $this->loggedSets->ajouter($_POST);
                break;

            case 'logged-set-supprimer':
                $this->loggedSets->confirmerSuppression($this->lireIdGet());
                break;

            case 'logged-set-supprimer-confirme':
                $this->exigerEcriture($_POST['csrf_token'] ?? null);
                $this->loggedSets->supprimer($this->lireIdPost());
                break;

            // --- Comptes ---
            case 'connexion':
                $this->utilisateurs->connexion();
                break;

            case 'authentifier':
                $this->exigerEcriture($_POST['csrf_token'] ?? null);
                $this->utilisateurs->authentifier($_POST);
                break;

            case 'inscription':
                $this->utilisateurs->inscription();
                break;

            case 'creer-compte':
                $this->exigerEcriture($_POST['csrf_token'] ?? null);
                $this->utilisateurs->creerCompte($_POST);
                break;

            case 'deconnexion':
                $this->exigerEcriture($_POST['csrf_token'] ?? null);
                $this->utilisateurs->deconnecter();
                break;

            default:
                $this->erreurs->afficher('Page introuvable.', 404);
        }
    }

    private function lireIdGet(): string
    {
        return $this->validerId(filter_input(INPUT_GET, 'id'));
    }

    private function lireIdPost(): string
    {
        return $this->validerId(filter_input(INPUT_POST, 'id'));
    }

    private function validerId($id): string
    {
        if (!is_string($id) || !preg_match('/^[A-Za-z0-9-]{1,36}$/', $id)) {
            throw new InvalidArgumentException('Identifiant invalide.', 400);
        }

        return $id;
    }

    private function exigerEcriture($jeton): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new RuntimeException('Méthode non permise.', 405);
        }

        if (!verifierJetonCsrf($jeton)) {
            throw new RuntimeException('Requête refusée.', 403);
        }
    }
}
