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
-- Table `reel-picker`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`users` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`users` (
  `idusers` INT NOT NULL AUTO_INCREMENT,
  `pseudo` TEXT NULL,
  `password` TEXT NULL,
  `email` VARCHAR(255) NULL,
  PRIMARY KEY (`idusers`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`movie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`movie` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`movie` (
  `idmovie` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NULL,
  `desc` TEXT NULL,
  `img` TEXT NULL,
  `grade` FLOAT NULL,
  PRIMARY KEY (`idmovie`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`tag` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`tag` (
  `idtag` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL,
  PRIMARY KEY (`idtag`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`users_like_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`users_like_tag` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`users_like_tag` (
  `users_idusers` INT NOT NULL,
  `tag_idtag` INT NOT NULL,
  PRIMARY KEY (`users_idusers`, `tag_idtag`),
  INDEX `fk_users_has_tag_tag1_idx` (`tag_idtag` ASC),
  INDEX `fk_users_has_tag_users_idx` (`users_idusers` ASC),
  CONSTRAINT `fk_users_has_tag_users`
    FOREIGN KEY (`users_idusers`)
    REFERENCES `reel-picker`.`users` (`idusers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_tag_tag1`
    FOREIGN KEY (`tag_idtag`)
    REFERENCES `reel-picker`.`tag` (`idtag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`users_dislike_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`users_dislike_tag` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`users_dislike_tag` (
  `users_idusers` INT NOT NULL,
  `tag_idtag` INT NOT NULL,
  PRIMARY KEY (`users_idusers`, `tag_idtag`),
  INDEX `fk_users_has_tag_tag2_idx` (`tag_idtag` ASC),
  INDEX `fk_users_has_tag_users1_idx` (`users_idusers` ASC),
  CONSTRAINT `fk_users_has_tag_users1`
    FOREIGN KEY (`users_idusers`)
    REFERENCES `reel-picker`.`users` (`idusers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_tag_tag2`
    FOREIGN KEY (`tag_idtag`)
    REFERENCES `reel-picker`.`tag` (`idtag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reel-picker`.`movie_has_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reel-picker`.`movie_has_tag` ;

CREATE TABLE IF NOT EXISTS `reel-picker`.`movie_has_tag` (
  `movie_idmovie` INT NOT NULL,
  `tag_idtag` INT NOT NULL,
  PRIMARY KEY (`movie_idmovie`, `tag_idtag`),
  INDEX `fk_movie_has_tag_tag1_idx` (`tag_idtag` ASC),
  INDEX `fk_movie_has_tag_movie1_idx` (`movie_idmovie` ASC),
  CONSTRAINT `fk_movie_has_tag_movie1`
    FOREIGN KEY (`movie_idmovie`)
    REFERENCES `reel-picker`.`movie` (`idmovie`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_movie_has_tag_tag1`
    FOREIGN KEY (`tag_idtag`)
    REFERENCES `reel-picker`.`tag` (`idtag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
