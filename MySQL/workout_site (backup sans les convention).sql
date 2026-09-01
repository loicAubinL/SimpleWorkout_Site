CREATE DATABASE workout_site;
USE workout_site;

CREATE TABLE users (
id CHAR(36) NOT NULL DEFAULT (UUID()),
email VARCHAR(255) NOT NULL UNIQUE,
password_hash VARCHAR(255) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE profiles (
user_id CHAR(36) NOT NULL,
avatar_url TEXT NULL,
language VARCHAR(10) NOT NULL DEFAULT 'fr',
unit VARCHAR(10) NOT NULL DEFAULT 'lbs',
PRIMARY KEY (user_id),
CONSTRAINT profiles_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user_progress (
user_id CHAR(36) NOT NULL,
current_position INT NOT NULL DEFAULT 0,
PRIMARY KEY (user_id),
CONSTRAINT user_progress_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE workout_splits (
id CHAR(36) NOT NULL DEFAULT (UUID()),
user_id CHAR(36) NOT NULL,
name TEXT NOT NULL,
is_active BOOLEAN NOT NULL DEFAULT FALSE,
current_position INT NOT NULL DEFAULT 0,
created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
override_day_id CHAR(36) NULL,
override_date DATE NULL,
PRIMARY KEY (id),
CONSTRAINT workout_splits_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE workout_days (
id CHAR(36) NOT NULL DEFAULT (UUID()),
user_id CHAR(36) NOT NULL,
day_of_week INT NULL,
name TEXT NOT NULL,
position INT NOT NULL DEFAULT 0,
created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
split_id CHAR(36) NULL,
is_rest_day BOOLEAN NOT NULL DEFAULT FALSE,
PRIMARY KEY (id),
CONSTRAINT workout_days_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id),
CONSTRAINT workout_days_split_id_fkey FOREIGN KEY (split_id) REFERENCES workout_splits(id)
) ENGINE=InnoDB;

ALTER TABLE workout_splits
	ADD CONSTRAINT workout_splits_override_day_id_fkey
    FOREIGN KEY (override_day_id) REFERENCES workout_days(id);
    
CREATE TABLE exercices (
id CHAR(36) NOT NULL DEFAULT (UUID()),
workout_day_id CHAR(36) NOT NULL,
name TEXT NOT NULL,
notes TEXT NULL,
sub1 TEXT NULL,
sub2 TEXT NULL,
target_sets INT NOT NULL DEFAULT 3,
target_reps TEXT NULL,
position INT NOT NULL DEFAULT 0,
created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
rir INT NULL,
subs JSON NULL,
PRIMARY KEY (id),
CONSTRAINT exercices_workout_day_id_fkey FOREIGN KEY (workout_day_id) REFERENCES workout_days(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE logged_sets (
id CHAR(36) NOT NULL DEFAULT (UUID()),
exercice_id CHAR(36) NOT NULL,
user_id CHAR(36) NOT NULL,
set_number INT NOT NULL,
weight DECIMAL(6,2) NULL,
reps INT NULL,
logged_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
CONSTRAINT logged_sets_exercice_id_fkey FOREIGN KEY (exercice_id) REFERENCES exercices(id) ON DELETE CASCADE,
CONSTRAINT logged_sets_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;
