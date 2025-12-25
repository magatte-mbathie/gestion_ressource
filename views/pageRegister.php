<?php
require_once __DIR__ . '/../lib/authen_lib.php';

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';
    $role = $_POST['role'] ?? 'eleve';
    
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        $erreur = 'Tous les champs sont obligatoires.';
    } elseif ($mot_de_passe !== $confirmation) {
        $erreur = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($mot_de_passe) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caractères.';
    } else {
        if (inscrireUtilisateur($nom, $prenom, $email, $mot_de_passe, $role)) {
            $succes = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
        } else {
            $erreur = 'Cet email est déjà utilisé.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestion de Cours</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h1>Inscription</h1>
            <?php if ($erreur): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($erreur); ?></div>
            <?php endif; ?>
            <?php if ($succes): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($succes); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle :</label>
                    <select id="role" name="role">
                        <option value="eleve">Élève</option>
                        <option value="professeur">Professeur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe :</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <div class="form-group">
                    <label for="confirmation">Confirmer le mot de passe :</label>
                    <input type="password" id="confirmation" name="confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
            <p class="text-center">
                Déjà un compte ? <a href="index.php?page=login">Se connecter</a>
            </p>
        </div>
    </div>
</body>
</html>

