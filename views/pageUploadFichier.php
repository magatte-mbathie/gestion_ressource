<?php
require_once __DIR__ . '/../lib/authen_lib.php';
require_once __DIR__ . '/../lib/CoursManager.class.php';

if (!estConnecte() || !estProfesseur()) {
    header('Location: index.php?page=accueil');
    exit;
}

$cours_id = $_POST['cours_id'] ?? null;
if (!$cours_id) {
    header('Location: index.php?page=accueil');
    exit;
}

// Vérifier que le cours appartient au professeur connecté
$coursManager = new CoursManager();
$cours = $coursManager->getCoursParId($cours_id);

if (!$cours || $cours['professeur_id'] != getUserId()) {
    header('Location: index.php?page=accueil');
    exit;
}

// Créer le dossier uploads s'il n'existe pas
$upload_dir = __DIR__ . '/../uploads/cours_' . $cours_id . '/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    $file = $_FILES['fichier'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $nom_original = $file['name'];
        $taille_fichier = $file['size'];
        $type_mime = $file['type'];
        $tmp_name = $file['tmp_name'];
        
        // Générer un nom unique pour le fichier
        $extension = pathinfo($nom_original, PATHINFO_EXTENSION);
        $nom_fichier = uniqid() . '_' . time() . '.' . $extension;
        $chemin_fichier = $upload_dir . $nom_fichier;
        
        if (move_uploaded_file($tmp_name, $chemin_fichier)) {
            $coursManager->ajouterFichier($cours_id, $nom_fichier, $nom_original, $chemin_fichier, $taille_fichier, $type_mime);
            header('Location: index.php?page=cours&id=' . $cours_id);
            exit;
        } else {
            header('Location: index.php?page=cours&id=' . $cours_id . '&erreur=upload');
            exit;
        }
    } else {
        header('Location: index.php?page=cours&id=' . $cours_id . '&erreur=upload');
        exit;
    }
}

header('Location: index.php?page=cours&id=' . $cours_id);
exit;
?>

