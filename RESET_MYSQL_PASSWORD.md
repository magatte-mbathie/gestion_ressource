# Réinitialiser le mot de passe MySQL

## Solution 1 : Réinitialiser le mot de passe root

### Étape 1 : Arrêter MySQL
```bash
brew services stop mysql
# ou
sudo /usr/local/mysql/support-files/mysql.server stop
```

### Étape 2 : Démarrer MySQL en mode sécurisé
```bash
sudo mysqld_safe --skip-grant-tables &
```

### Étape 3 : Se connecter sans mot de passe
```bash
mysql -u root
```

### Étape 4 : Dans MySQL, exécuter :
```sql
USE mysql;
UPDATE user SET authentication_string=PASSWORD('') WHERE User='root';
FLUSH PRIVILEGES;
EXIT;
```

### Étape 5 : Redémarrer MySQL normalement
```bash
brew services restart mysql
```

## Solution 2 : Utiliser le script PHP (Plus simple !)

Au lieu d'utiliser la ligne de commande MySQL, utilisez le script PHP via le navigateur :

1. Ouvrez : **http://localhost:8000/add_users.php**
2. Le script utilisera les identifiants configurés dans `lib/dsn_perso.php`
3. Aucun mot de passe en ligne de commande nécessaire !

## Solution 3 : Configurer un utilisateur MySQL sans mot de passe

Si vous préférez utiliser MySQL sans mot de passe pour le développement local :

```bash
mysql -u root -p
```

Puis dans MySQL :
```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY '';
FLUSH PRIVILEGES;
```

Puis modifiez `lib/dsn_perso.php` pour avoir un mot de passe vide :
```php
define('DB_PASS', '');
```

## Solution 4 : Créer un nouvel utilisateur MySQL

```bash
mysql -u root -p
```

Puis :
```sql
CREATE USER 'gestion_cours'@'localhost' IDENTIFIED BY 'votre_mot_de_passe';
GRANT ALL PRIVILEGES ON gestion_cours.* TO 'gestion_cours'@'localhost';
FLUSH PRIVILEGES;
```

Puis modifiez `lib/dsn_perso.php` :
```php
define('DB_USER', 'gestion_cours');
define('DB_PASS', 'votre_mot_de_passe');
```

