<?php
require_once __DIR__ . '/../lib/dsn_perso.php';
require_once __DIR__ . '/../lib/authen_lib.php';
require_once __DIR__ . '/../lib/CoursManager.class.php';

if (!estConnecte()) {
    header('Location: index.php?page=login');
    exit;
}

$fichier_id = $_GET['id'] ?? null;
if (!$fichier_id) {
    header('Location: index.php?page=accueil');
    exit;
}

$coursManager = new CoursManager();
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM fichiers_cours WHERE id = ?");
$stmt->execute([$fichier_id]);
$fichier = $stmt->fetch();

if (!$fichier || !file_exists($fichier['chemin_fichier'])) {
    header('Location: index.php?page=accueil');
    exit;
}

// Vérifier que l'utilisateur a accès au cours
$cours = $coursManager->getCoursParId($fichier['cours_id']);
if (!$cours) {
    header('Location: index.php?page=accueil');
    exit;
}

// Les élèves peuvent télécharger tous les fichiers, les professeurs seulement leurs cours
if (estEleve() || (estProfesseur() && $cours['professeur_id'] == getUserId())) {
    header('Content-Type: ' . $fichier['type_mime']);
    header('Content-Disposition: attachment; filename="' . $fichier['nom_original'] . '"');
    header('Content-Length: ' . filesize($fichier['chemin_fichier']));
    readfile($fichier['chemin_fichier']);
    exit;
}

header('Location: index.php?page=accueil');
exit;
?>

