<?php
require_once __DIR__ . '/dsn_perso.php';

session_start();

// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

// Fonction pour obtenir l'ID de l'utilisateur connecté
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Fonction pour obtenir le rôle de l'utilisateur connecté
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

// Fonction pour vérifier si l'utilisateur est professeur
function estProfesseur() {
    return getUserRole() === 'professeur';
}

// Fonction pour vérifier si l'utilisateur est élève
function estEleve() {
    return getUserRole() === 'eleve';
}

// Fonction de connexion
function connecterUtilisateur($email, $mot_de_passe) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email, mot_de_passe, role FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    return false;
}

// Fonction de déconnexion
function deconnecterUtilisateur() {
    session_unset();
    session_destroy();
}

// Fonction d'inscription
function inscrireUtilisateur($nom, $prenom, $email, $mot_de_passe, $role = 'eleve') {
    $pdo = getDBConnection();
    
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return false; // Email déjà utilisé
    }
    
    // Hasher le mot de passe
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    
    // Insérer le nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $email, $mot_de_passe_hash, $role]);
}

// Fonction pour obtenir les informations de l'utilisateur connecté
function getUtilisateurConnecte() {
    if (!estConnecte()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'nom' => $_SESSION['user_nom'],
        'prenom' => $_SESSION['user_prenom'],
        'role' => $_SESSION['user_role']
    ];
}
?>

