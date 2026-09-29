-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema workout_site
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema workout_site
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `workout_site` DEFAULT CHARACTER SET utf8mb4 ;
-- -----------------------------------------------------
-- Schema simpleworkout_site
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema simpleworkout_site
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `simpleworkout_site` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
USE `workout_site` ;

-- -----------------------------------------------------
-- Table `workout_site`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `workout_site`.`users` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `workout_site`.`workout_splits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `workout_site`.`workout_splits` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `user_id` CHAR(36) NOT NULL,
  `name` TEXT NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '0',
  `current_position` INT NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `override_day_id` CHAR(36) NULL DEFAULT NULL,
  `override_date` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `workout_splits_user_id_fkey` (`user_id` ASC) VISIBLE,
  INDEX `workout_splits_override_day_id_fkey` (`override_day_id` ASC) VISIBLE,
  CONSTRAINT `workout_splits_override_day_id_fkey`
    FOREIGN KEY (`override_day_id`)
    REFERENCES `workout_site`.`workout_days` (`id`),
  CONSTRAINT `workout_splits_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `workout_site`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `workout_site`.`workout_days`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `workout_site`.`workout_days` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `user_id` CHAR(36) NOT NULL,
  `day_of_week` INT NULL DEFAULT NULL,
  `name` TEXT NOT NULL,
  `position` INT NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `split_id` CHAR(36) NULL DEFAULT NULL,
  `is_rest_day` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `workout_days_user_id_fkey` (`user_id` ASC) VISIBLE,
  INDEX `workout_days_split_id_fkey` (`split_id` ASC) VISIBLE,
  CONSTRAINT `workout_days_split_id_fkey`
    FOREIGN KEY (`split_id`)
    REFERENCES `workout_site`.`workout_splits` (`id`),
  CONSTRAINT `workout_days_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `workout_site`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `workout_site`.`exercices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `workout_site`.`exercices` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `workout_day_id` CHAR(36) NOT NULL,
  `name` TEXT NOT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `sub1` TEXT NULL DEFAULT NULL,
  `sub2` TEXT NULL DEFAULT NULL,
  `target_sets` INT NOT NULL DEFAULT '3',
  `target_reps` TEXT NULL DEFAULT NULL,
  `position` INT NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `rir` INT NULL DEFAULT NULL,
  `subs` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `exercices_workout_day_id_fkey` (`workout_day_id` ASC) VISIBLE,
  CONSTRAINT `exercices_workout_day_id_fkey`
    FOREIGN KEY (`workout_day_id`)
    REFERENCES `workout_site`.`workout_days` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `workout_site`.`logged_sets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `workout_site`.`logged_sets` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `exercice_id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `set_number` INT NOT NULL,
  `weight` DECIMAL(6,2) NULL DEFAULT NULL,
  `reps` INT NULL DEFAULT NULL,
  `logged_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `logged_sets_exercice_id_fkey` (`exercice_id` ASC) VISIBLE,
  INDEX `logged_sets_user_id_fkey` (`user_id` ASC) VISIBLE,
  CONSTRAINT `logged_sets_exercice_id_fkey`
    FOREIGN KEY (`exercice_id`)
    REFERENCES `workout_site`.`exercices` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `logged_sets_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `workout_site`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `workout_site`.`profiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `workout_site`.`profiles` (
  `user_id` CHAR(36) NOT NULL,
  `avatar_url` TEXT NULL DEFAULT NULL,
  `language` VARCHAR(10) NOT NULL DEFAULT 'fr',
  `unit` VARCHAR(10) NOT NULL DEFAULT 'lbs',
  PRIMARY KEY (`user_id`),
  CONSTRAINT `profiles_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `workout_site`.`users` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `workout_site`.`user_progress`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `workout_site`.`user_progress` (
  `user_id` CHAR(36) NOT NULL,
  `current_position` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_progress_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `workout_site`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

USE `simpleworkout_site` ;

-- -----------------------------------------------------
-- Table `simpleworkout_site`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simpleworkout_site`.`users` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `simpleworkout_site`.`workout_splits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simpleworkout_site`.`workout_splits` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `user_id` CHAR(36) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '0',
  `current_position` INT NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `override_day_id` CHAR(36) NULL DEFAULT NULL,
  `override_date` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `workout_splits_user_id_fkey` (`user_id` ASC) VISIBLE,
  INDEX `workout_splits_override_day_id_fkey` (`override_day_id` ASC) VISIBLE,
  CONSTRAINT `workout_splits_override_day_id_fkey`
    FOREIGN KEY (`override_day_id`)
    REFERENCES `simpleworkout_site`.`workout_days` (`id`),
  CONSTRAINT `workout_splits_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `simpleworkout_site`.`users` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `simpleworkout_site`.`workout_days`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simpleworkout_site`.`workout_days` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `user_id` CHAR(36) NOT NULL,
  `day_of_week` INT NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `position` INT NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `workout_split_id` CHAR(36) NULL DEFAULT NULL,
  `is_rest_day` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `workout_days_user_id_fkey` (`user_id` ASC) VISIBLE,
  INDEX `workout_days_workout_split_id_fkey` (`workout_split_id` ASC) VISIBLE,
  CONSTRAINT `workout_days_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `simpleworkout_site`.`users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `workout_days_workout_split_id_fkey`
    FOREIGN KEY (`workout_split_id`)
    REFERENCES `simpleworkout_site`.`workout_splits` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `simpleworkout_site`.`exercises`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simpleworkout_site`.`exercises` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `workout_day_id` CHAR(36) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `sub1` VARCHAR(255) NULL DEFAULT NULL,
  `sub2` VARCHAR(255) NULL DEFAULT NULL,
  `target_sets` INT NOT NULL DEFAULT '3',
  `target_reps` VARCHAR(50) NULL DEFAULT NULL,
  `position` INT NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `rir` INT NULL DEFAULT NULL,
  `subs` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `exercises_workout_day_id_fkey` (`workout_day_id` ASC) VISIBLE,
  CONSTRAINT `exercises_workout_day_id_fkey`
    FOREIGN KEY (`workout_day_id`)
    REFERENCES `simpleworkout_site`.`workout_days` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `simpleworkout_site`.`logged_sets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simpleworkout_site`.`logged_sets` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `exercise_id` CHAR(36) NOT NULL,
  `user_id` CHAR(36) NOT NULL,
  `set_number` INT NOT NULL,
  `weight` DECIMAL(6,2) NULL DEFAULT NULL,
  `reps` INT NULL DEFAULT NULL,
  `logged_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `logged_sets_exercise_id_fkey` (`exercise_id` ASC) VISIBLE,
  INDEX `logged_sets_user_id_fkey` (`user_id` ASC) VISIBLE,
  CONSTRAINT `logged_sets_exercise_id_fkey`
    FOREIGN KEY (`exercise_id`)
    REFERENCES `simpleworkout_site`.`exercises` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `logged_sets_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `simpleworkout_site`.`users` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `simpleworkout_site`.`profiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simpleworkout_site`.`profiles` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `user_id` CHAR(36) NOT NULL,
  `avatar_url` VARCHAR(255) NULL DEFAULT NULL,
  `language` VARCHAR(10) NOT NULL DEFAULT 'fr',
  `unit` VARCHAR(10) NOT NULL DEFAULT 'kg',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `profiles_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `simpleworkout_site`.`users` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `simpleworkout_site`.`user_progress`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simpleworkout_site`.`user_progress` (
  `id` CHAR(36) NOT NULL DEFAULT (uuid()),
  `user_id` CHAR(36) NOT NULL,
  `current_position` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `user_progress_user_id_fkey`
    FOREIGN KEY (`user_id`)
    REFERENCES `simpleworkout_site`.`users` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
