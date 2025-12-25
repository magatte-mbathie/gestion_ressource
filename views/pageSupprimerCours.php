<?php
require_once __DIR__ . '/../lib/authen_lib.php';
require_once __DIR__ . '/../lib/CoursManager.class.php';

if (!estConnecte() || !estProfesseur()) {
    header('Location: index.php?page=accueil');
    exit;
}

$cours_id = $_GET['id'] ?? null;
if (!$cours_id) {
    header('Location: index.php?page=accueil');
    exit;
}

// Vérifier que le cours appartient au professeur
$coursManager = new CoursManager();
$cours = $coursManager->getCoursParId($cours_id);

if ($cours && $cours['professeur_id'] == getUserId()) {
    $coursManager->supprimerCours($cours_id);
}

header('Location: index.php?page=accueil');
exit;
?>

