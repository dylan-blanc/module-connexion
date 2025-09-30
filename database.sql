-- Création de la base de données moduleconnexion
CREATE DATABASE IF NOT EXISTS moduleconnexion;
USE moduleconnexion;

-- Création de la table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion d'un utilisateur admin par défaut
-- Mot de passe: admin123 (hashé avec password_hash)
INSERT INTO utilisateurs (login, prenom, nom, password) VALUES 
('admin', 'Admin', 'Admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');