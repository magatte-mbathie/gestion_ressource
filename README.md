# Système de Gestion de Cours

Un système web complet pour permettre aux professeurs de déposer des cours et aux élèves de les consulter et télécharger.

## Fonctionnalités

### Pour les Professeurs
- Créer des cours avec titre et description
- Téléverser des fichiers (PDF, documents, etc.) pour chaque cours
- Gérer leurs cours (modifier, supprimer)
- Supprimer des fichiers de cours

### Pour les Élèves
- Consulter tous les cours disponibles
- Voir les détails de chaque cours
- Télécharger les fichiers de cours

## Installation

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)

### Étapes d'installation

1. **Cloner ou télécharger le projet**

2. **Configurer la base de données**
   - Créer une base de données MySQL
   - Importer le fichier `database.sql` dans votre base de données
   - Modifier les paramètres de connexion dans `lib/dsn_perso.php` :
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'gestion_cours');
     define('DB_USER', 'votre_utilisateur');
     define('DB_PASS', 'votre_mot_de_passe');
     ```

3. **Configurer les permissions**
   - Créer un dossier `uploads` à la racine du projet avec les permissions d'écriture :
     ```bash
     mkdir uploads
     chmod 755 uploads
     ```

4. **Accéder à l'application**
   - Ouvrir votre navigateur et aller à `http://localhost/gestion_ressource/`
   - Compte administrateur par défaut :
     - Email : `admin@ecole.fr`
     - Mot de passe : `admin123`

## Structure du projet

```
gestion_ressource/
├── css/
│   └── style.css          # Styles CSS
├── lib/
│   ├── authen_lib.php     # Fonctions d'authentification
│   ├── dsn_perso.php      # Configuration base de données
│   └── CoursManager.class.php  # Gestion des cours
├── views/
│   ├── pageLogin.php      # Page de connexion
│   ├── pageRegister.php   # Page d'inscription
│   ├── pageAccueil.php    # Page d'accueil
│   ├── pageCours.php      # Détail d'un cours
│   ├── pageCreerCours.php # Création de cours
│   └── ...                # Autres pages
├── uploads/               # Dossier pour les fichiers uploadés
├── index.php              # Point d'entrée principal
├── database.sql           # Script SQL de création
└── README.md              # Ce fichier
```

## Utilisation

### Inscription
1. Cliquer sur "S'inscrire"
2. Remplir le formulaire (choisir le rôle : Professeur ou Élève)
3. Se connecter avec vos identifiants

### Créer un cours (Professeur)
1. Se connecter en tant que professeur
2. Cliquer sur "Créer un cours"
3. Remplir le titre et la description
4. Cliquer sur "Créer le cours"

### Ajouter des fichiers à un cours (Professeur)
1. Aller sur la page d'un cours
2. Utiliser le formulaire "Ajouter un fichier"
3. Sélectionner le fichier et cliquer sur "Téléverser"

### Consulter et télécharger des cours (Élève)
1. Se connecter en tant qu'élève
2. Parcourir les cours disponibles sur la page d'accueil
3. Cliquer sur "Voir le cours" pour voir les détails
4. Cliquer sur "Télécharger" pour télécharger un fichier

## Sécurité

- Les mots de passe sont hashés avec `password_hash()`
- Protection contre les injections SQL avec les requêtes préparées
- Vérification des permissions pour chaque action
- Validation des fichiers uploadés

## Notes

- Assurez-vous que le dossier `uploads` a les bonnes permissions
- En production, désactivez l'affichage des erreurs PHP
- Changez le mot de passe administrateur par défaut
- Configurez les limites d'upload dans `php.ini` si nécessaire

## Support

Pour toute question ou problème, consultez la documentation PHP et MySQL.
