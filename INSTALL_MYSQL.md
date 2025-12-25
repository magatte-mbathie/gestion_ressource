# Installation de MySQL sur macOS

## Option 1 : Installation via Homebrew (Recommandé)

```bash
# Installer Homebrew si ce n'est pas déjà fait
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Installer MySQL
brew install mysql

# Démarrer MySQL
brew services start mysql

# Sécuriser l'installation (optionnel mais recommandé)
mysql_secure_installation
```

Après l'installation, ajoutez MySQL au PATH dans votre `~/.zshrc` :
```bash
echo 'export PATH="/opt/homebrew/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

## Option 2 : Installation via le package officiel

1. Téléchargez MySQL depuis : https://dev.mysql.com/downloads/mysql/
2. Installez le package `.dmg`
3. Suivez l'assistant d'installation
4. MySQL sera automatiquement ajouté au PATH

## Option 3 : Utiliser MariaDB (Alternative à MySQL)

```bash
brew install mariadb
brew services start mariadb
```

## Vérification de l'installation

```bash
# Vérifier que MySQL est installé
mysql --version

# Vérifier que MySQL est en cours d'exécution
brew services list | grep mysql
```

## Configuration après installation

1. **Créer la base de données** :
```bash
mysql -u root -p < database.sql
```

2. **Ajouter les utilisateurs** :
```bash
mysql -u root -p gestion_cours < insert_users.sql
```

Ou utilisez le script PHP via le navigateur : http://localhost:8000/add_users.php

