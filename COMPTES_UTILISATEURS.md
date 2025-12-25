# Comptes Utilisateurs

## 🔐 Mot de passe par défaut
**Tous les comptes utilisent le mot de passe : `password123`**

## 👨‍🏫 Professeurs (5 comptes)

| Nom | Prénom | Email | Mot de passe |
|-----|--------|-------|--------------|
| Dupont | Jean | jean.dupont@ecole.fr | password123 |
| Martin | Marie | marie.martin@ecole.fr | password123 |
| Bernard | Pierre | pierre.bernard@ecole.fr | password123 |
| Dubois | Sophie | sophie.dubois@ecole.fr | password123 |
| Lefebvre | Thomas | thomas.lefebvre@ecole.fr | password123 |

## 👨‍🎓 Élèves (10 comptes)

| Nom | Prénom | Email | Mot de passe |
|-----|--------|-------|--------------|
| Moreau | Lucas | lucas.moreau@ecole.fr | password123 |
| Laurent | Emma | emma.laurent@ecole.fr | password123 |
| Simon | Léo | leo.simon@ecole.fr | password123 |
| Michel | Chloé | chloe.michel@ecole.fr | password123 |
| Garcia | Hugo | hugo.garcia@ecole.fr | password123 |
| David | Léa | lea.david@ecole.fr | password123 |
| Bertrand | Nathan | nathan.bertrand@ecole.fr | password123 |
| Roux | Manon | manon.roux@ecole.fr | password123 |
| Vincent | Alexandre | alexandre.vincent@ecole.fr | password123 |
| Fournier | Camille | camille.fournier@ecole.fr | password123 |

## 🔑 Compte Administrateur

| Nom | Prénom | Email | Mot de passe |
|-----|--------|-------|--------------|
| Admin | Professeur | admin@ecole.fr | admin123 |

## 📝 Comment ajouter ces utilisateurs

### Méthode 1 : Via le navigateur (Recommandé)
1. Accédez à : http://localhost:8000/add_users.php
2. Le script ajoutera automatiquement tous les utilisateurs
3. Vous verrez un résumé des comptes créés

### Méthode 2 : Via MySQL en ligne de commande
```bash
mysql -u root -p gestion_cours < insert_users.sql
```

### Méthode 3 : Via phpMyAdmin
1. Ouvrez phpMyAdmin
2. Sélectionnez la base de données `gestion_cours`
3. Allez dans l'onglet "SQL"
4. Copiez-collez le contenu de `insert_users.sql`
5. Cliquez sur "Exécuter"

## ⚠️ Notes importantes

- Tous les mots de passe sont hashés avec `password_hash()` de PHP
- Le hash utilisé correspond au mot de passe `password123`
- Les emails doivent être uniques (contrainte UNIQUE dans la base de données)
- Si un utilisateur existe déjà, il ne sera pas dupliqué

## 🔒 Sécurité

**⚠️ IMPORTANT :** Changez les mots de passe par défaut en production !

Pour changer un mot de passe, vous pouvez utiliser cette requête SQL :
```sql
UPDATE utilisateurs 
SET mot_de_passe = '$2y$10$NOUVEAU_HASH_ICI' 
WHERE email = 'email@exemple.fr';
```

Ou utilisez cette fonction PHP pour générer un nouveau hash :
```php
$nouveau_hash = password_hash('nouveau_mot_de_passe', PASSWORD_DEFAULT);
```

