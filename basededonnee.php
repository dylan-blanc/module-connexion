CREATE DATABASE moduleconnexion;
USE moduleconnexion;

CREATE TABLE utilisateurs (
id INT AUTO_INCREMENT PRIMARY KEY,
login VARCHAR(255),
prenom VARCHAR(255),
nom VARCHAR(255),
password VARCHAR(255),
email VARCHAR(255)
);

INSERT INTO utilisateurs (login, prenom, nom, password, email) VALUES
('admin', 'admin', 'admin', '$2y$10$MALOfHqANtnksyth0/ocuus/RRsfVqgrUQde69ni3K9lGdN0e688e', 'admin@gmail.com');

<!-- login : admin :/: mdp : admin -->


<!-- "root" mdp : "" >vide< -->