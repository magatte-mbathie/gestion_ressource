<?php
require_once __DIR__ . '/../lib/authen_lib.php';
require_once __DIR__ . '/../lib/CoursManager.class.php';

if (!estConnecte()) {
    header('Location: index.php?page=login');
    exit;
}

$coursManager = new CoursManager();
$user = getUtilisateurConnecte();

if (estProfesseur()) {
    $cours = $coursManager->getCoursParProfesseur($user['id']);
} else {
    $cours = $coursManager->getTousLesCours();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion de Cours</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1>Gestion de Cours</h1>
            <div class="nav-links">
                <span>Bonjour, <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> (<?php echo htmlspecialchars($user['role']); ?>)</span>
                <?php if (estProfesseur()): ?>
                    <a href="index.php?page=creer-cours" class="btn btn-primary">Créer un cours</a>
                <?php endif; ?>
                <a href="index.php?page=logout" class="btn btn-secondary">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2><?php echo estProfesseur() ? 'Mes cours' : 'Cours disponibles'; ?></h2>
        
        <?php if (empty($cours)): ?>
            <div class="alert alert-info">
                <?php echo estProfesseur() ? 'Vous n\'avez pas encore créé de cours.' : 'Aucun cours disponible pour le moment.'; ?>
            </div>
        <?php else: ?>
            <div class="cours-grid">
                <?php foreach ($cours as $c): ?>
                    <div class="cours-card">
                        <h3><?php echo htmlspecialchars($c['titre']); ?></h3>
                        <p class="cours-description"><?php echo htmlspecialchars($c['description']); ?></p>
                        <p class="cours-meta">
                            <strong>Professeur :</strong> <?php echo htmlspecialchars($c['professeur_prenom'] . ' ' . $c['professeur_nom']); ?><br>
                            <strong>Date :</strong> <?php echo date('d/m/Y', strtotime($c['date_creation'])); ?>
                        </p>
                        <div class="cours-actions">
                            <a href="index.php?page=cours&id=<?php echo $c['id']; ?>" class="btn btn-primary">Voir le cours</a>
                            <?php if (estProfesseur() && $c['professeur_id'] == $user['id']): ?>
                                <a href="index.php?page=supprimer-cours&id=<?php echo $c['id']; ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">Supprimer</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

