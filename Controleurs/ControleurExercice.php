<?php

declare(strict_types=1);

class ControleurExercice
{
    private $exercices;
    private $vue;
    private $erreurs;

    public function __construct(ExerciceModele $exercices, Vue $vue, ControleurErreur $erreurs)
    {
        $this->exercices = $exercices;
        $this->vue = $vue;
        $this->erreurs = $erreurs;
    }


    public function formulaire(string $id): void
    {
        $exercice = $this->exercices->obtenirExerciceParId($id);

        if ($exercice === null) {
            $this->erreurs->afficher('Exercice introuvable.', 404);
            return;
        }

        $valeurs = [
            'name' => $exercice['name'],
            'target_sets' => (string) $exercice['target_sets'],
            'target_reps' => (string) $exercice['target_reps'],
            'notes' => (string) ($exercice['notes'] ?? ''),
        ];

        $this->afficherFormulaire($id, $valeurs, []);
    }

    public function enregistrer(string $id, array $donnees): void
    {
        if ($this->exercices->obtenirExerciceParId($id) === null) {
            $this->erreurs->afficher('Exercice introuvable.', 404);
            return;
        }

        $valeurs = [
            'name' => trim((string) ($donnees['name'] ?? '')),
            'target_sets' => trim((string) ($donnees['target_sets'] ?? '')),
            'target_reps' => trim((string) ($donnees['target_reps'] ?? '')),
            'notes' => trim((string) ($donnees['notes'] ?? '')),
        ];

        $erreurs = [];

        if ($valeurs['name'] === '' || mb_strlen($valeurs['name']) > 100) {
            $erreurs['name'] = 'Le nom est obligatoire (100 caractères maximum).';
        }

        $series = filter_var($valeurs['target_sets'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 20]]);
        if ($series === false) {
            $erreurs['target_sets'] = 'Le nombre de séries doit être un entier entre 1 et 20.';
        }

        if ($valeurs['target_reps'] === '' || mb_strlen($valeurs['target_reps']) > 20) {
            $erreurs['target_reps'] = 'Les répétitions sont obligatoires (ex. 8 ou 8-10).';
        }

        if (mb_strlen($valeurs['notes']) > 500) {
            $erreurs['notes'] = 'Les notes ne doivent pas dépasser 500 caractères.';
        }

        if ($erreurs !== []) {
            // On réaffiche le formulaire avec les valeurs saisies
            http_response_code(422);
            $this->afficherFormulaire($id, $valeurs, $erreurs);
            return;
        }

        $this->exercices->modifier($id, $valeurs['name'], $series, $valeurs['target_reps'], $valeurs['notes']);
        rediriger('exercice/' . urlencode($id));
    }

    private function afficherFormulaire(string $id, array $valeurs, array $erreurs): void
    {
        $this->vue->afficher('exercice/modifier', compact('id', 'valeurs', 'erreurs'), 'Modifier un exercice');
    }
}
