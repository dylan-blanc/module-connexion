# Guide de Déploiement - Module Connexion

## Installation rapide

### 1. Préparation de l'environnement
```bash
# Cloner le projet
git clone https://github.com/dylan-blanc/module-connexion.git
cd module-connexion

# Vérifier PHP (version 7.4+ requise)
php --version
```

### 2. Configuration de la base de données

#### Option A : Import direct
```bash
mysql -u root -p < database.sql
```

#### Option B : Création manuelle
```sql
-- Se connecter à MySQL
mysql -u root -p

-- Créer la base
CREATE DATABASE moduleconnexion;
USE moduleconnexion;

-- Créer la table
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Créer l'utilisateur admin (mot de passe: admin123)
INSERT INTO utilisateurs (login, prenom, nom, password) VALUES 
('admin', 'Admin', 'Admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');
```

### 3. Configuration PHP
Modifier `config/database.php` si nécessaire :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'moduleconnexion');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Démarrage du serveur
```bash
# Serveur de développement PHP
php -S localhost:8000

# Ou placer dans votre serveur web (Apache/Nginx)
```

### 5. Premier test
- Ouvrir http://localhost:8000
- Se connecter avec : admin / admin123
- Tester l'inscription d'un nouvel utilisateur

## Structure complète du projet

```
module-connexion/
├── accueil.php          # Page d'accueil avec présentation
├── admin.php            # Interface d'administration (admin uniquement)
├── connexion.php        # Page de connexion avec gestion de session
├── inscription.php      # Inscription avec validation de mot de passe
├── profil.php          # Gestion du profil utilisateur
├── index.php           # Point d'entrée (redirection)
├── demo.html           # Page de démonstration statique
├── database.sql        # Structure et données de base
├── .gitignore          # Fichiers à ignorer
├── README.md           # Documentation complète
├── config/
│   └── database.php    # Configuration PDO MySQL
├── includes/
│   ├── header.php      # Header avec navigation
│   ├── footer.php      # Footer commun
│   └── session.php     # Fonctions de gestion de session
└── css/
    └── style.css       # Styles CSS responsifs
```

## Fonctionnalités implémentées

### ✅ Authentification
- Inscription avec validation des champs
- Confirmation de mot de passe
- Connexion sécurisée avec session
- Déconnexion propre
- Hachage des mots de passe (password_hash)

### ✅ Gestion utilisateur
- Modification du profil (nom, prénom)
- Changement de mot de passe
- Validation côté client et serveur

### ✅ Administration
- Page réservée à l'utilisateur "admin"
- Liste de tous les utilisateurs
- Suppression d'utilisateurs (sauf admin)
- Statistiques du système

### ✅ Sécurité
- Protection contre l'injection SQL (requêtes préparées)
- Validation et échappement des données
- Contrôle d'accès aux pages sensibles
- Sessions sécurisées

### ✅ Interface utilisateur
- Design responsive et moderne
- Navigation adaptative
- Messages de feedback
- Formulaires validés
- CSS professionnel

## Tests effectués

Tous les fichiers ont été validés :
```
✓ accueil.php - Syntaxe OK
✓ admin.php - Syntaxe OK  
✓ config/database.php - Syntaxe OK
✓ connexion.php - Syntaxe OK
✓ includes/footer.php - Syntaxe OK
✓ includes/header.php - Syntaxe OK
✓ includes/session.php - Syntaxe OK
✓ index.php - Syntaxe OK
✓ inscription.php - Syntaxe OK
✓ profil.php - Syntaxe OK
```

Fonctionnalités vérifiées :
- ✅ Structure des fichiers et dossiers
- ✅ CSS responsive (4890 bytes)
- ✅ Scripts SQL de création
- ✅ Hachage des mots de passe
- ✅ Gestion des sessions
- ✅ Validation des formulaires

## Points d'attention

1. **Base de données** : S'assurer que MySQL est démarré
2. **Permissions** : Vérifier les droits en écriture sur les sessions
3. **PHP** : Extensions PDO et MySQL activées
4. **Sécurité** : Changer les identifiants par défaut en production

Le module est prêt pour la production après configuration de la base de données !