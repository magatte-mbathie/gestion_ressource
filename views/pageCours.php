<?php
require_once __DIR__ . '/../lib/authen_lib.php';
require_once __DIR__ . '/../lib/CoursManager.class.php';

if (!estConnecte()) {
    header('Location: index.php?page=login');
    exit;
}

$cours_id = $_GET['id'] ?? null;
if (!$cours_id) {
    header('Location: index.php?page=accueil');
    exit;
}

$coursManager = new CoursManager();
$cours = $coursManager->getCoursParId($cours_id);
$fichiers = $coursManager->getFichiersCours($cours_id);
$user = getUtilisateurConnecte();

if (!$cours) {
    header('Location: index.php?page=accueil');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cours['titre']); ?> - Gestion de Cours</title>
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
        <div class="cours-detail">
            <h1><?php echo htmlspecialchars($cours['titre']); ?></h1>
            <p class="cours-meta">
                <strong>Professeur :</strong> <?php echo htmlspecialchars($cours['professeur_prenom'] . ' ' . $cours['professeur_nom']); ?><br>
                <strong>Date de création :</strong> <?php echo date('d/m/Y à H:i', strtotime($cours['date_creation'])); ?>
            </p>
            <div class="cours-description-full">
                <h2>Description</h2>
                <p><?php echo nl2br(htmlspecialchars($cours['description'])); ?></p>
            </div>

            <div class="fichiers-section">
                <h2>Fichiers du cours</h2>
                <?php if (estProfesseur() && $cours['professeur_id'] == $user['id']): ?>
                    <form method="POST" action="index.php?page=upload-fichier" enctype="multipart/form-data" class="upload-form">
                        <input type="hidden" name="cours_id" value="<?php echo $cours_id; ?>">
                        <div class="form-group">
                            <label for="fichier">Ajouter un fichier :</label>
                            <input type="file" id="fichier" name="fichier" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Téléverser</button>
                    </form>
                <?php endif; ?>

                <?php if (empty($fichiers)): ?>
                    <div class="alert alert-info">Aucun fichier disponible pour ce cours.</div>
                <?php else: ?>
                    <div class="fichiers-list">
                        <?php foreach ($fichiers as $fichier): ?>
                            <div class="fichier-item">
                                <div class="fichier-info">
                                    <span class="fichier-nom"><?php echo htmlspecialchars($fichier['nom_original']); ?></span>
                                    <span class="fichier-meta">
                                        <?php echo CoursManager::formaterTailleFichier($fichier['taille_fichier']); ?> - 
                                        <?php echo date('d/m/Y H:i', strtotime($fichier['date_upload'])); ?>
                                    </span>
                                </div>
                                <div class="fichier-actions">
                                    <a href="index.php?page=telecharger&id=<?php echo $fichier['id']; ?>" class="btn btn-primary">Télécharger</a>
                                    <?php if (estProfesseur() && $cours['professeur_id'] == $user['id']): ?>
                                        <a href="index.php?page=supprimer-fichier&id=<?php echo $fichier['id']; ?>&cours_id=<?php echo $cours_id; ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">Supprimer</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

