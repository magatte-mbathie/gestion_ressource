<?php
/**
 * Script de test de connexion à la base de données
 * Utilisez ce script pour vérifier que votre configuration fonctionne
 */

require_once __DIR__ . '/lib/dsn_perso.php';

echo "<h1>Test de connexion à la base de données</h1>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Connexion à la base de données réussie !</p>";
    
    // Tester si les tables existent
    $tables = ['utilisateurs', 'cours', 'fichiers_cours', 'cours_eleves'];
    echo "<h2>Vérification des tables :</h2>";
    echo "<ul>";
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<li style='color: green;'>✅ Table '$table' existe</li>";
        } else {
            echo "<li style='color: red;'>❌ Table '$table' n'existe pas</li>";
        }
    }
    echo "</ul>";
    
    // Compter les utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM utilisateurs");
    $result = $stmt->fetch();
    echo "<p><strong>Nombre d'utilisateurs :</strong> " . $result['count'] . "</p>";
    
    // Compter les cours
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM cours");
    $result = $stmt->fetch();
    echo "<p><strong>Nombre de cours :</strong> " . $result['count'] . "</p>";
    
    echo "<p style='color: green;'><strong>✅ Tout est prêt ! Vous pouvez accéder à l'application.</strong></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erreur de connexion : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Vérifiez :</strong></p>";
    echo "<ul>";
    echo "<li>Que MySQL/MariaDB est démarré</li>";
    echo "<li>Que la base de données 'gestion_cours' existe</li>";
    echo "<li>Que les identifiants dans lib/dsn_perso.php sont corrects</li>";
    echo "<li>Que vous avez importé le fichier database.sql</li>";
    echo "</ul>";
}

