<?php
/**
 * Script PHP pour ajouter des utilisateurs (professeurs et élèves) à la base de données
 * Utilisez ce script si vous préférez l'exécuter via le navigateur
 */

require_once __DIR__ . '/lib/dsn_perso.php';

// Tous les mots de passe sont : password123
$password_hash = password_hash('password123', PASSWORD_DEFAULT);

try {
    $pdo = getDBConnection();
    
    echo "<h1>Ajout d'utilisateurs à la base de données</h1>";
    echo "<style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        ul { line-height: 1.8; }
    </style>";
    
    // Professeurs
    $professeurs = [
        ['Dupont', 'Jean', 'jean.dupont@ecole.fr'],
        ['Martin', 'Marie', 'marie.martin@ecole.fr'],
        ['Bernard', 'Pierre', 'pierre.bernard@ecole.fr'],
        ['Dubois', 'Sophie', 'sophie.dubois@ecole.fr'],
        ['Lefebvre', 'Thomas', 'thomas.lefebvre@ecole.fr'],
    ];
    
    // Élèves
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
    
    echo "<h2>Ajout des professeurs :</h2><ul>";
    $professeurs_ajoutes = 0;
    foreach ($professeurs as $prof) {
        try {
            $stmt->execute([$prof[0], $prof[1], $prof[2], $password_hash, 'professeur']);
            echo "<li class='success'>✅ Professeur ajouté : {$prof[1]} {$prof[0]} ({$prof[2]})</li>";
            $professeurs_ajoutes++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<li class='info'>ℹ️ Professeur déjà existant : {$prof[1]} {$prof[0]} ({$prof[2]})</li>";
            } else {
                echo "<li class='error'>❌ Erreur pour {$prof[1]} {$prof[0]} : " . htmlspecialchars($e->getMessage()) . "</li>";
            }
        }
    }
    echo "</ul>";
    
    echo "<h2>Ajout des élèves :</h2><ul>";
    $eleves_ajoutes = 0;
    foreach ($eleves as $eleve) {
        try {
            $stmt->execute([$eleve[0], $eleve[1], $eleve[2], $password_hash, 'eleve']);
            echo "<li class='success'>✅ Élève ajouté : {$eleve[1]} {$eleve[0]} ({$eleve[2]})</li>";
            $eleves_ajoutes++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<li class='info'>ℹ️ Élève déjà existant : {$eleve[1]} {$eleve[0]} ({$eleve[2]})</li>";
            } else {
                echo "<li class='error'>❌ Erreur pour {$eleve[1]} {$eleve[0]} : " . htmlspecialchars($e->getMessage()) . "</li>";
            }
        }
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<h2 class='success'>✅ Résumé :</h2>";
    echo "<p><strong>Professeurs ajoutés :</strong> $professeurs_ajoutes</p>";
    echo "<p><strong>Élèves ajoutés :</strong> $eleves_ajoutes</p>";
    echo "<p><strong>Mot de passe pour tous les comptes :</strong> <code>password123</code></p>";
    
    echo "<hr>";
    echo "<h2>Comptes créés :</h2>";
    echo "<h3>Professeurs :</h3><ul>";
    foreach ($professeurs as $prof) {
        echo "<li><strong>{$prof[1]} {$prof[0]}</strong> - Email: {$prof[2]} - Mot de passe: password123</li>";
    }
    echo "</ul>";
    
    echo "<h3>Élèves :</h3><ul>";
    foreach ($eleves as $eleve) {
        echo "<li><strong>{$eleve[1]} {$eleve[0]}</strong> - Email: {$eleve[2]} - Mot de passe: password123</li>";
    }
    echo "</ul>";
    
    echo "<p><a href='index.php?page=login'>Aller à la page de connexion</a></p>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vérifiez que :</p><ul>";
    echo "<li>La base de données 'gestion_cours' existe</li>";
    echo "<li>Les identifiants dans lib/dsn_perso.php sont corrects</li>";
    echo "<li>Le fichier database.sql a été importé</li>";
    echo "</ul>";
}

