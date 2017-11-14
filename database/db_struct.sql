-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema reel-picker
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `reel-picker` ;

-- -----------------------------------------------------
-- Schema reel-picker
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `reel-picker` DEFAULT CHARACTER SET utf8 ;
USE `reel-picker` ;

-- -----------------------------------------------------
-- Table `reel-picker`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`user` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `pseudo` VARCHAR(200) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`movie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`movie` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`movie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `rating` FLOAT NULL,
  `desc` TEXT NULL,
  `image` TEXT NULL,
  `date` VARCHAR(45) NULL,
  `runtime` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`tag` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`user_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`user_tags` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`user_tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `tag_id` INT NOT NULL,
  `rating` FLOAT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_tags_user_idx` (`user_id` ASC),
  INDEX `fk_user_tags_tag1_idx` (`tag_id` ASC),
  CONSTRAINT `fk_user_tags_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `reel-picker`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_tags_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `reel-picker`.`tag` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`user_movies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`user_movies` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`user_movies` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `movie_id` INT NOT NULL,
  `rating` FLOAT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_movies_user1_idx` (`user_id` ASC),
  INDEX `fk_user_movies_movie1_idx` (`movie_id` ASC),
  CONSTRAINT `fk_user_movies_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `reel-picker`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_movies_movie1`
    FOREIGN KEY (`movie_id`)
    REFERENCES `reel-picker`.`movie` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`movie_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`movie_tags` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`movie_tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `movie_id` INT NOT NULL,
  `tag_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_movie_tags_movie1_idx` (`movie_id` ASC),
  INDEX `fk_movie_tags_tag1_idx` (`tag_id` ASC),
  CONSTRAINT `fk_movie_tags_movie1`
    FOREIGN KEY (`movie_id`)
    REFERENCES `reel-picker`.`movie` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_movie_tags_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `reel-picker`.`tag` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
