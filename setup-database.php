<?php
/**
 * Script complet pour créer la base de données et ajouter les utilisateurs
 * Ce script gère automatiquement la création de la base de données
 */

// Configuration temporaire pour créer la base de données (sans spécifier de base)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Modifiez si nécessaire

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Configuration de la base de données</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .success { color: green; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: red; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0; }
        h1 { color: #667eea; }
        h2 { color: #555; margin-top: 30px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        form { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        input[type='password'], input[type='text'] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #667eea; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #764ba2; }
    </style>
</head>
<body>
    <h1>🔧 Configuration de la base de données</h1>";

// Si un formulaire est soumis avec le mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['db_pass'])) {
    $db_pass = $_POST['db_pass'];
}

try {
    // Étape 1 : Se connecter à MySQL sans spécifier de base de données
    echo "<h2>Étape 1 : Connexion à MySQL</h2>";
    $dsn = "mysql:host=$db_host;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    echo "<div class='success'>✅ Connexion à MySQL réussie !</div>";
    
    // Étape 2 : Créer la base de données si elle n'existe pas
    echo "<h2>Étape 2 : Création de la base de données</h2>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS gestion_cours CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<div class='success'>✅ Base de données 'gestion_cours' créée ou existe déjà</div>";
    
    // Étape 3 : Utiliser la base de données
    $pdo->exec("USE gestion_cours");
    
    // Étape 4 : Créer les tables
    echo "<h2>Étape 3 : Création des tables</h2>";
    
    // Table utilisateurs
    $pdo->exec("CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        mot_de_passe VARCHAR(255) NOT NULL,
        role ENUM('professeur', 'eleve') NOT NULL DEFAULT 'eleve',
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<div class='success'>✅ Table 'utilisateurs' créée</div>";
    
    // Table cours
    $pdo->exec("CREATE TABLE IF NOT EXISTS cours (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(255) NOT NULL,
        description TEXT,
        professeur_id INT NOT NULL,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (professeur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<div class='success'>✅ Table 'cours' créée</div>";
    
    // Table fichiers_cours
    $pdo->exec("CREATE TABLE IF NOT EXISTS fichiers_cours (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cours_id INT NOT NULL,
        nom_fichier VARCHAR(255) NOT NULL,
        nom_original VARCHAR(255) NOT NULL,
        chemin_fichier VARCHAR(500) NOT NULL,
        taille_fichier INT NOT NULL,
        type_mime VARCHAR(100),
        date_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<div class='success'>✅ Table 'fichiers_cours' créée</div>";
    
    // Table cours_eleves
    $pdo->exec("CREATE TABLE IF NOT EXISTS cours_eleves (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cours_id INT NOT NULL,
        eleve_id INT NOT NULL,
        date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
        FOREIGN KEY (eleve_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
        UNIQUE KEY unique_cours_eleve (cours_id, eleve_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<div class='success'>✅ Table 'cours_eleves' créée</div>";
    
    // Étape 5 : Ajouter l'utilisateur admin s'il n'existe pas
    echo "<h2>Étape 4 : Ajout de l'utilisateur administrateur</h2>";
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = 'admin@ecole.fr'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $admin_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Admin', 'Professeur', 'admin@ecole.fr', $admin_hash, 'professeur']);
        echo "<div class='success'>✅ Utilisateur administrateur créé (admin@ecole.fr / admin123)</div>";
    } else {
        echo "<div class='info'>ℹ️ Utilisateur administrateur existe déjà</div>";
    }
    
    // Étape 6 : Ajouter les professeurs et élèves
    echo "<h2>Étape 5 : Ajout des utilisateurs</h2>";
    $password_hash = password_hash('password123', PASSWORD_DEFAULT);
    
    $professeurs = [
        ['Dupont', 'Jean', 'jean.dupont@ecole.fr'],
        ['Martin', 'Marie', 'marie.martin@ecole.fr'],
        ['Bernard', 'Pierre', 'pierre.bernard@ecole.fr'],
        ['Dubois', 'Sophie', 'sophie.dubois@ecole.fr'],
        ['Lefebvre', 'Thomas', 'thomas.lefebvre@ecole.fr'],
    ];
    
    $eleves = [
        ['Moreau', 'Lucas', 'lucas.moreau@ecole.fr'],
        ['Laurent', 'Emma', 'emma.laurent@ecole.fr'],
        ['Simon', 'Léo', 'leo.simon@ecole.fr'],
        ['Michel', 'Chloé', 'chloe.michel@ecole.fr'],
        ['Garcia', 'Hugo', 'hugo.garcia@ecole.fr'],
        ['David', 'Léa', 'lea.david@ecole.fr'],
        ['Bertrand', 'Nathan', 'nathan.bertrand@ecole.fr'],
        ['Roux', 'Manon', 'manon.roux@ecole.fr'],
        ['Vincent', 'Alexandre', 'alexandre.vincent@ecole.fr'],
        ['Fournier', 'Camille', 'camille.fournier@ecole.fr'],
    ];
    
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
    
    $professeurs_ajoutes = 0;
    $eleves_ajoutes = 0;
    
    echo "<h3>Professeurs :</h3><ul>";
    foreach ($professeurs as $prof) {
        try {
            $stmt->execute([$prof[0], $prof[1], $prof[2], $password_hash, 'professeur']);
            echo "<li class='success'>✅ {$prof[1]} {$prof[0]} ({$prof[2]})</li>";
            $professeurs_ajoutes++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<li class='info'>ℹ️ {$prof[1]} {$prof[0]} existe déjà</li>";
            }
        }
    }
    echo "</ul>";
    
    echo "<h3>Élèves :</h3><ul>";
    foreach ($eleves as $eleve) {
        try {
            $stmt->execute([$eleve[0], $eleve[1], $eleve[2], $password_hash, 'eleve']);
            echo "<li class='success'>✅ {$eleve[1]} {$eleve[0]} ({$eleve[2]})</li>";
            $eleves_ajoutes++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<li class='info'>ℹ️ {$eleve[1]} {$eleve[0]} existe déjà</li>";
            }
        }
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<div class='success'><h2>✅ Configuration terminée avec succès !</h2>";
    echo "<p><strong>Professeurs ajoutés :</strong> $professeurs_ajoutes</p>";
    echo "<p><strong>Élèves ajoutés :</strong> $eleves_ajoutes</p>";
    echo "<p><strong>Mot de passe pour tous les comptes (sauf admin) :</strong> <code>password123</code></p>";
    echo "<p><strong>Compte admin :</strong> admin@ecole.fr / admin123</p></div>";
    
    echo "<p><a href='index.php?page=login' style='display: inline-block; margin-top: 20px; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Aller à la page de connexion</a></p>";
    
} catch (PDOException $e) {
    if ($e->getCode() == 1045) {
        echo "<div class='error'><h2>❌ Erreur d'authentification MySQL</h2>";
        echo "<p>Le mot de passe MySQL est incorrect ou l'utilisateur n'a pas les permissions.</p>";
        echo "<p>Veuillez entrer le mot de passe MySQL root :</p>";
        echo "<form method='POST'>";
        echo "<input type='password' name='db_pass' placeholder='Mot de passe MySQL root' required>";
        echo "<button type='submit'>Réessayer</button>";
        echo "</form>";
        echo "<p><strong>Astuce :</strong> Si vous n'avez pas de mot de passe, laissez le champ vide et modifiez <code>lib/dsn_perso.php</code> pour avoir <code>DB_PASS = ''</code></p></div>";
    } else {
        echo "<div class='error'><h2>❌ Erreur :</h2><p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
    }
}

echo "</body></html>";
?>

