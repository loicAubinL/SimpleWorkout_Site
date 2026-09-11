-- Script d'ajout de données réalistes — chapitre 2
-- Crée d'abord les dépendances (user, profil, progression, split, jours),
-- puis 10 enregistrements réalistes dans la table principale "exercises".

USE SimpleWorkout_site;

-- 1 utilisateur de test
INSERT INTO users (id, email, password_hash) VALUES
('a1111111-1111-1111-1111-111111111111', 'loic.test@example.com', '$2y$10$exempledehashquinestpasunmotdepasseenclair');

INSERT INTO profiles (id, user_id, language, unit) VALUES
('a2222222-2222-2222-2222-222222222222', 'a1111111-1111-1111-1111-111111111111', 'fr', 'kg');

INSERT INTO user_progress (id, user_id, current_position) VALUES
('a3333333-3333-3333-3333-333333333333', 'a1111111-1111-1111-1111-111111111111', 0);

-- 1 split actif : Push / Pull / Legs
INSERT INTO workout_splits (id, user_id, name, is_active, current_position) VALUES
('b1111111-1111-1111-1111-111111111111', 'a1111111-1111-1111-1111-111111111111', 'Push Pull Legs', TRUE, 0);

-- 3 jours dans ce split
INSERT INTO workout_days (id, user_id, name, position, workout_split_id, is_rest_day) VALUES
('c1111111-1111-1111-1111-111111111111', 'a1111111-1111-1111-1111-111111111111', 'Push', 0, 'b1111111-1111-1111-1111-111111111111', FALSE),
('c2222222-2222-2222-2222-222222222222', 'a1111111-1111-1111-1111-111111111111', 'Pull', 1, 'b1111111-1111-1111-1111-111111111111', FALSE),
('c3333333-3333-3333-3333-333333333333', 'a1111111-1111-1111-1111-111111111111', 'Legs', 2, 'b1111111-1111-1111-1111-111111111111', FALSE);

-- 10 enregistrements réalistes dans la table principale : exercises
INSERT INTO exercises (id, workout_day_id, name, notes, target_sets, target_reps, position, rir) VALUES
('d1111111-1111-1111-1111-111111111111', 'c1111111-1111-1111-1111-111111111111', 'Développé couché barre', 'Focus sur la descente contrôlée', 4, '6-8', 0, 2),
('d2222222-2222-2222-2222-222222222222', 'c1111111-1111-1111-1111-111111111111', 'Développé militaire haltères', NULL, 3, '8-10', 1, 2),
('d3333333-3333-3333-3333-333333333333', 'c1111111-1111-1111-1111-111111111111', 'Écarté couché haltères', 'Garder les coudes légèrement fléchis', 3, '10-12', 2, 1),
('d4444444-4444-4444-4444-444444444444', 'c1111111-1111-1111-1111-111111111111', 'Extension triceps poulie', NULL, 3, '12-15', 3, 1),
('d5555555-5555-5555-5555-555555555555', 'c2222222-2222-2222-2222-222222222222', 'Traction pronation', 'Amplitude complète', 4, '6-8', 0, 2),
('d6666666-6666-6666-6666-666666666666', 'c2222222-2222-2222-2222-222222222222', 'Rowing barre', NULL, 4, '8-10', 1, 2),
('d7777777-7777-7777-7777-777777777777', 'c2222222-2222-2222-2222-222222222222', 'Tirage vertical prise serrée', NULL, 3, '10-12', 2, 1),
('d8888888-8888-8888-8888-888888888888', 'c2222222-2222-2222-2222-222222222222', 'Curl biceps barre EZ', 'Ne pas balancer le dos', 3, '10-12', 3, 1),
('d9999999-9999-9999-9999-999999999999', 'c3333333-3333-3333-3333-333333333333', 'Squat arrière', 'Descendre sous parallèle', 4, '5-8', 0, 2),
('da111111-1111-1111-1111-111111111111', 'c3333333-3333-3333-3333-333333333333', 'Presse à cuisses', NULL, 3, '10-12', 1, 2);
