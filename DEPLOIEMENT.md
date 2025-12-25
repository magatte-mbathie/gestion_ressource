# Guide de Déploiement

## 🚀 Déploiement Rapide

### Option 1 : Script automatique (Recommandé)

```bash
./deploy.sh
```

Ce script va :
- ✅ Vérifier les prérequis (PHP, MySQL)
- ✅ Créer le dossier uploads
- ✅ Vérifier la configuration
- ✅ Vous guider pour créer la base de données

### Option 2 : Déploiement manuel

#### 1. Configuration de la base de données

```bash
# Importer la base de données
mysql -u root -p < database.sql

# Ou via phpMyAdmin : importer database.sql
```

#### 2. Configuration des identifiants

Éditez `lib/dsn_perso.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_cours');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
```

#### 3. Créer le dossier uploads

```bash
mkdir uploads
chmod 755 uploads
```

#### 4. Tester la connexion

Accédez à : `http://localhost:8000/test-connection.php`

## 🌐 Démarrage du serveur

### Serveur PHP intégré (Développement)

```bash
./start-server.sh
```

Ou manuellement :
```bash
php -S localhost:8000
```

Le site sera accessible à : **http://localhost:8000**

### Apache/Nginx (Production)

1. **Apache** : Configurez un VirtualHost pointant vers ce dossier
2. **Nginx** : Configurez un server block pointant vers ce dossier

## ✅ Vérification

1. **Test de connexion** : http://localhost:8000/test-connection.php
2. **Page de connexion** : http://localhost:8000/index.php?page=login
3. **Compte admin** :
   - Email : `admin@ecole.fr`
   - Mot de passe : `admin123`

## 📋 Checklist de déploiement

- [ ] PHP 7.4+ installé
- [ ] MySQL/MariaDB installé et démarré
- [ ] Base de données `gestion_cours` créée
- [ ] Fichier `database.sql` importé
- [ ] `lib/dsn_perso.php` configuré
- [ ] Dossier `uploads` créé avec permissions 755
- [ ] Serveur web démarré
- [ ] Test de connexion réussi

## 🔧 Dépannage

### Erreur de connexion à la base de données

1. Vérifiez que MySQL est démarré :
   ```bash
   # macOS
   brew services start mysql
   
   # Linux
   sudo systemctl start mysql
   ```

2. Vérifiez les identifiants dans `lib/dsn_perso.php`

3. Testez la connexion :
   ```bash
   mysql -u root -p -e "SHOW DATABASES;"
   ```

### Erreur d'upload de fichiers

1. Vérifiez les permissions du dossier uploads :
   ```bash
   chmod 755 uploads
   ```

2. Vérifiez la configuration PHP (`php.ini`) :
   ```
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

### Page blanche

1. Activez l'affichage des erreurs dans `.htaccess`
2. Vérifiez les logs PHP
3. Vérifiez que toutes les dépendances sont chargées

## 🌍 Déploiement en production

### Sécurité

1. **Désactivez l'affichage des erreurs** dans `.htaccess` :
   ```apache
   php_flag display_errors Off
   ```

2. **Changez le mot de passe admin** par défaut

3. **Protégez le dossier uploads** (déjà fait dans `.htaccess`)

4. **Utilisez HTTPS** avec un certificat SSL

5. **Limitez les tailles d'upload** selon vos besoins

### Performance

1. Activez le cache PHP (OPcache)
2. Configurez la mise en cache des fichiers statiques
3. Optimisez la base de données (indexes)

## 📞 Support

En cas de problème :
1. Vérifiez les logs d'erreur PHP
2. Vérifiez les logs MySQL
3. Testez avec `test-connection.php`
4. Consultez la documentation PHP/MySQL

