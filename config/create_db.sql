CREATE DATABASE IF NOT EXISTS spotify;

CREATE USER 'spotify_user'@'localhost' IDENTIFIED BY 'spotify_pass';
GRANT ALL PRIVILEGES ON spotify. * TO 'spotify_user'@'localhost';

USE spotify;

CREATE TABLE token (
   id INT NOT NULL AUTO_INCREMENT,
   token  VARCHAR(255) NOT NULL,
   refresh_token  VARCHAR(255) NOT NULL,
   expire DATETIME NOT NULL,
   CONSTRAINT token_pk PRIMARY KEY (id)
);

CREATE TABLE user (
   id INT NOT NULL AUTO_INCREMENT,
   spotify_id VARCHAR(40) NOT NULL,
   name  VARCHAR(255) NOT NULL,
   email VARCHAR(255) NOT NULL UNIQUE,
   token_id int                 ,
   CONSTRAINT user_pk PRIMARY KEY (id),
   FOREIGN KEY (token_id) REFERENCES token(id)
   ON DELETE CASCADE
);

