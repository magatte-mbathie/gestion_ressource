<?php
// Fichier principal de routage
require_once __DIR__ . '/lib/authen_lib.php';

// Récupérer la page demandée
$page = $_GET['page'] ?? 'accueil';

// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion (sauf pour login et register)
if (!estConnecte() && $page !== 'login' && $page !== 'register') {
    $page = 'login';
}

// Routage des pages
switch ($page) {
    case 'login':
        require_once __DIR__ . '/views/pageLogin.php';
        break;
    
    case 'register':
        require_once __DIR__ . '/views/pageRegister.php';
        break;
    
    case 'logout':
        require_once __DIR__ . '/views/pageLogout.php';
        break;
    
    case 'accueil':
        require_once __DIR__ . '/views/pageAccueil.php';
        break;
    
    case 'cours':
        require_once __DIR__ . '/views/pageCours.php';
        break;
    
    case 'creer-cours':
        require_once __DIR__ . '/views/pageCreerCours.php';
        break;
    
    case 'upload-fichier':
        require_once __DIR__ . '/views/pageUploadFichier.php';
        break;
    
    case 'telecharger':
        require_once __DIR__ . '/views/pageTelecharger.php';
        break;
    
    case 'supprimer-fichier':
        require_once __DIR__ . '/views/pageSupprimerFichier.php';
        break;
    
    case 'supprimer-cours':
        require_once __DIR__ . '/views/pageSupprimerCours.php';
        break;
    
    default:
        require_once __DIR__ . '/views/pageAccueil.php';
        break;
}
?>
