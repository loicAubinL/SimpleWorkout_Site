DROP DATABASE IF EXISTS SimpleWorkout_site;

CREATE DATABASE SimpleWorkout_site
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE SimpleWorkout_site;

CREATE TABLE users (
  id CHAR(36) NOT NULL DEFAULT (UUID()),
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE profiles (
  id CHAR(36) NOT NULL DEFAULT (UUID()),
  user_id CHAR(36) NOT NULL UNIQUE,
  avatar_url VARCHAR(255) NULL,
  language VARCHAR(10) NOT NULL DEFAULT 'fr',
  unit VARCHAR(10) NOT NULL DEFAULT 'kg',
  PRIMARY KEY (id),
  CONSTRAINT profiles_user_id_fkey
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user_progress (
  id CHAR(36) NOT NULL DEFAULT (UUID()),
  user_id CHAR(36) NOT NULL UNIQUE,
  current_position INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  CONSTRAINT user_progress_user_id_fkey
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE workout_splits (
  id CHAR(36) NOT NULL DEFAULT (UUID()),
  user_id CHAR(36) NOT NULL,
  name VARCHAR(255) NOT NULL,
  is_active BOOLEAN NOT NULL DEFAULT FALSE,
  current_position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  override_day_id CHAR(36) NULL,
  override_date DATE NULL,
  PRIMARY KEY (id),
  CONSTRAINT workout_splits_user_id_fkey
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE workout_days (
  id CHAR(36) NOT NULL DEFAULT (UUID()),
  user_id CHAR(36) NOT NULL,
  day_of_week INT NULL,
  name VARCHAR(255) NOT NULL,
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  workout_split_id CHAR(36) NULL,
  is_rest_day BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (id),
  CONSTRAINT workout_days_user_id_fkey
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT workout_days_workout_split_id_fkey
    FOREIGN KEY (workout_split_id) REFERENCES workout_splits(id) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE workout_splits
  ADD CONSTRAINT workout_splits_override_day_id_fkey
  FOREIGN KEY (override_day_id) REFERENCES workout_days(id);
  
CREATE TABLE exercises (
  id CHAR(36) NOT NULL DEFAULT (UUID()),
  workout_day_id CHAR(36) NOT NULL,
  name VARCHAR(255) NOT NULL,
  notes TEXT NULL,
  sub1 VARCHAR(255) NULL,
  sub2 VARCHAR(255) NULL,
  target_sets INT NOT NULL DEFAULT 3,
  target_reps VARCHAR(50) NULL,
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  rir INT NULL,
  subs JSON NULL,
  PRIMARY KEY (id),
  CONSTRAINT exercises_workout_day_id_fkey
    FOREIGN KEY (workout_day_id) REFERENCES workout_days(id) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE logged_sets (
  id CHAR(36) NOT NULL DEFAULT (UUID()),
  exercise_id CHAR(36) NOT NULL,
  user_id CHAR(36) NOT NULL,
  set_number INT NOT NULL,
  weight DECIMAL(6,2) NULL,
  reps INT NULL,
  logged_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT logged_sets_exercise_id_fkey
    FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE CASCADE,
  CONSTRAINT logged_sets_user_id_fkey
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;