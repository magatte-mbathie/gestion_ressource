<?php
require_once __DIR__ . '/../lib/authen_lib.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    
    if (connecterUtilisateur($email, $mot_de_passe)) {
        header('Location: index.php?page=accueil');
        exit;
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion de Cours</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h1>Connexion</h1>
            <?php if ($erreur): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($erreur); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe :</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
            <p class="text-center">
                Pas encore de compte ? <a href="index.php?page=register">S'inscrire</a>
            </p>
        </div>
    </div>
</body>
</html>

