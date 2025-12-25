<?php
require_once __DIR__ . '/../lib/authen_lib.php';
require_once __DIR__ . '/../lib/CoursManager.class.php';

if (!estConnecte() || !estProfesseur()) {
    header('Location: index.php?page=accueil');
    exit;
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($titre)) {
        $erreur = 'Le titre est obligatoire.';
    } else {
        $coursManager = new CoursManager();
        if ($coursManager->creerCours($titre, $description, getUserId())) {
            $succes = 'Cours créé avec succès !';
            header('Location: index.php?page=accueil');
            exit;
        } else {
            $erreur = 'Erreur lors de la création du cours.';
        }
    }
}

$user = getUtilisateurConnecte();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un cours - Gestion de Cours</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1>Gestion de Cours</h1>
            <div class="nav-links">
                <a href="index.php?page=accueil" class="btn btn-secondary">Retour à l'accueil</a>
                <a href="index.php?page=logout" class="btn btn-secondary">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Créer un nouveau cours</h1>
        
        <?php if ($erreur): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($erreur); ?></div>
        <?php endif; ?>
        <?php if ($succes): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($succes); ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="cours-form">
            <div class="form-group">
                <label for="titre">Titre du cours :</label>
                <input type="text" id="titre" name="titre" required placeholder="Ex: Mathématiques - Algèbre">
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="6" placeholder="Décrivez le contenu de ce cours..."></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Créer le cours</button>
                <a href="index.php?page=accueil" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>

