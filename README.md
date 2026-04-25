# Eva — Application Web de Gestion de Tâches

## Prérequis
- XAMPP (Apache + MySQL + PHP 8.0+)

---

## Installation

### 1. Placer le projet dans XAMPP

Copier le dossier `Eva/` dans :
```
C:/xampp/htdocs/Eva/
```

### 2. Créer la base de données

1. Ouvrir **phpMyAdmin** → `http://localhost/phpmyadmin`
2. Importer le fichier `Eva/config/projetphp.sql`
   - Menu **Importer** → choisir le fichier → **Exécuter**

Ou coller ce contenu directement dans l'onglet **SQL** :
```sql
CREATE DATABASE IF NOT EXISTS projetphp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE projetphp;
-- (voir config/projetphp.sql pour le script complet)
```

### 3. Configurer la connexion (si nécessaire)

Modifier `config/Database.php` si vos identifiants XAMPP sont différents :
```php
$host   = 'localhost';
$dbname = 'projetphp';
$user   = 'root';
$pass   = '';        // Laisser vide par défaut sur XAMPP
```

### 4. Permissions du dossier uploads

Sur Linux/Mac :
```bash
chmod 775 public/uploads/
```
Sur Windows : XAMPP gère cela automatiquement.

### 5. Lancer l'application

Ouvrir dans le navigateur :
```
http://localhost/Eva/public/index.php
```

---

## Structure MVC

```
Eva/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php      (Login / Logout / Signup)
│   │   ├── TaskController.php      (CRUD tâches + fichiers)
│   │   └── CategoryController.php  (Gestion catégories)
│   ├── Models/
│   │   ├── User.php
│   │   ├── UserManager.php
│   │   ├── Task.php
│   │   ├── TaskManager.php
│   │   ├── Category.php
│   │   └── CategoryManager.php
│   └── Views/
│       ├── layout/
│       │   ├── header.php
│       │   └── footer.php
│       ├── Login.php
│       ├── Signup.php
│       ├── Dashboard.php
│       ├── AddTask.php
│       ├── EditTask.php
│       └── Editcategory.php
├── public/
│   ├── css/
│   │   └── style.css
│   ├── uploads/              (fichiers uploadés)
│   └── index.php             (point d'entrée unique)
└── config/
    ├── Database.php           (connexion PDO)
    └── projetphp.sql             (script SQL)
```

---

## Fonctionnalités implémentées

### Authentification
- ✅ Inscription (nom, email, mot de passe hashé bcrypt)
- ✅ Connexion avec vérification sécurisée
- ✅ Déconnexion + destruction de session
- ✅ Session regeneration (protection fixation de session)

### Gestion des tâches
- ✅ Ajouter une tâche (titre, catégorie, fichier optionnel)
- ✅ Consulter la liste des tâches (avec filtres)
- ✅ Modifier une tâche (titre, catégorie, remplacement fichier)
- ✅ Supprimer une tâche (+ suppression fichier associé)
- ✅ Marquer terminé / remettre en cours (toggle)

### Gestion des catégories
- ✅ Créer une catégorie
- ✅ Modifier une catégorie
- ✅ Supprimer une catégorie
- ✅ Associer une tâche à une catégorie

### Gestion des fichiers
- ✅ Upload fichier (stockage dans `public/uploads/`)
- ✅ Téléchargement sécurisé via `index.php?action=download_file`
- ✅ Remplacement de fichier à l'édition
- ✅ Suppression automatique à la suppression de tâche
- ✅ Validation extension et taille (5 Mo max)
- ✅ Nom de fichier nettoyé (protection path traversal)

### Sécurité
- ✅ Mots de passe hashés (bcrypt)
- ✅ Requêtes préparées PDO (protection SQL injection)
- ✅ htmlspecialchars() sur toutes les sorties (protection XSS)
- ✅ Vérification ownership (user_id sur toutes les requêtes)
- ✅ Validation des entrées côté serveur
- ✅ Superglobales : $_GET, $_POST, $_SESSION, $_FILES
- ✅ header() pour toutes les redirections
- ✅ Dossier uploads en dehors de l'accès direct aux scripts PHP

---

## Technologies
- PHP 8.0+ (POO, PDO, sessions)
- MySQL 5.7+ / MariaDB
- HTML5 / CSS3 (pur, sans framework)
- Architecture MVC
- Google Fonts : Sora + JetBrains Mono
