# Module Connexion PHP

Un module de connexion complet développé en PHP avec PDO pour la gestion des utilisateurs.

## Fonctionnalités

### 🔐 Authentification sécurisée
- Inscription avec validation et confirmation de mot de passe
- Connexion avec gestion de session
- Déconnexion sécurisée
- Hachage des mots de passe avec `password_hash()`

### 👤 Gestion de profil
- Modification des informations personnelles
- Changement de mot de passe
- Interface utilisateur intuitive

### 🛡️ Administration
- Page d'administration réservée à l'utilisateur "admin"
- Liste de tous les utilisateurs inscrits
- Possibilité de supprimer des utilisateurs (sauf admin)
- Statistiques du système

## Structure du projet

```
module-connexion/
├── accueil.php          # Page d'accueil
├── admin.php            # Interface d'administration
├── connexion.php        # Page de connexion
├── inscription.php      # Page d'inscription
├── profil.php          # Gestion du profil utilisateur
├── index.php           # Redirection vers accueil
├── database.sql        # Structure de la base de données
├── config/
│   └── database.php    # Configuration PDO
├── includes/
│   ├── header.php      # Header commun
│   ├── footer.php      # Footer commun
│   └── session.php     # Gestion des sessions
└── css/
    └── style.css       # Styles CSS
```

## Installation

### 1. Base de données
Créez une base de données MySQL et importez le fichier `database.sql` :

```sql
mysql -u root -p < database.sql
```

Ou exécutez les commandes SQL suivantes :

```sql
CREATE DATABASE IF NOT EXISTS moduleconnexion;
USE moduleconnexion;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Utilisateur admin par défaut (login: admin, password: admin123)
INSERT INTO utilisateurs (login, prenom, nom, password) VALUES 
('admin', 'Admin', 'Admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');
```

### 2. Configuration
Modifiez le fichier `config/database.php` selon votre configuration :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'moduleconnexion');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Serveur web
Placez les fichiers dans votre serveur web (Apache, Nginx) ou utilisez le serveur de développement PHP :

```bash
php -S localhost:8000
```

## Utilisation

### Compte administrateur par défaut
- **Login :** admin
- **Mot de passe :** admin123

### Pages disponibles

1. **Accueil** (`accueil.php`)
   - Page d'accueil avec présentation des fonctionnalités
   - Navigation adaptée selon le statut de connexion

2. **Inscription** (`inscription.php`)
   - Formulaire d'inscription avec validation
   - Confirmation du mot de passe
   - Vérification de l'unicité du login

3. **Connexion** (`connexion.php`)
   - Formulaire de connexion
   - Gestion des sessions
   - Redirection après connexion

4. **Profil** (`profil.php`)
   - Modification des informations personnelles
   - Changement de mot de passe
   - Accessible uniquement aux utilisateurs connectés

5. **Administration** (`admin.php`)
   - Liste des utilisateurs
   - Suppression d'utilisateurs
   - Statistiques du système
   - Réservée à l'utilisateur "admin"

## Sécurité

### Mesures implémentées
- ✅ Hachage des mots de passe avec `password_hash()`
- ✅ Validation et échappement des données utilisateur
- ✅ Protection contre l'injection SQL (requêtes préparées)
- ✅ Gestion sécurisée des sessions
- ✅ Contrôle d'accès aux pages sensibles
- ✅ Validation côté client et serveur

### Bonnes pratiques
- Mots de passe minimum 6 caractères
- Confirmation des mots de passe
- Messages d'erreur informatifs
- Protection CSRF basique
- Validation des données d'entrée

## Technologies utilisées

- **PHP 7.4+** avec PDO
- **MySQL** pour la base de données
- **HTML5** et **CSS3** pour l'interface
- **JavaScript** pour la validation côté client

## Design

Interface responsive avec :
- Navigation adaptative
- Formulaires stylisés
- Messages de feedback
- Design moderne et épuré
- Compatible mobile

## Développement

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Extension PHP PDO activée

### Tests
Tous les fichiers PHP ont été validés syntaxiquement et testés pour :
- ✅ Syntaxe PHP correcte
- ✅ Structure des fichiers
- ✅ Fonctionnalités de sécurité
- ✅ Gestion des sessions
- ✅ Validation des formulaires

## Licence

Ce projet est fourni à des fins éducatives et de démonstration.