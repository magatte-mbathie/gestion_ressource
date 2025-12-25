<?php
require_once __DIR__ . '/dsn_perso.php';

class CoursManager {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDBConnection();
    }
    
    // Créer un nouveau cours
    public function creerCours($titre, $description, $professeur_id) {
        $stmt = $this->pdo->prepare("INSERT INTO cours (titre, description, professeur_id) VALUES (?, ?, ?)");
        return $stmt->execute([$titre, $description, $professeur_id]);
    }
    
    // Obtenir tous les cours d'un professeur
    public function getCoursParProfesseur($professeur_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cours WHERE professeur_id = ? ORDER BY date_creation DESC");
        $stmt->execute([$professeur_id]);
        return $stmt->fetchAll();
    }
    
    // Obtenir tous les cours disponibles pour les élèves
    public function getTousLesCours() {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.nom as professeur_nom, u.prenom as professeur_prenom 
            FROM cours c 
            JOIN utilisateurs u ON c.professeur_id = u.id 
            ORDER BY c.date_creation DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Obtenir un cours par son ID
    public function getCoursParId($cours_id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.nom as professeur_nom, u.prenom as professeur_prenom 
            FROM cours c 
            JOIN utilisateurs u ON c.professeur_id = u.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$cours_id]);
        return $stmt->fetch();
    }
    
    // Obtenir les fichiers d'un cours
    public function getFichiersCours($cours_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM fichiers_cours WHERE cours_id = ? ORDER BY date_upload DESC");
        $stmt->execute([$cours_id]);
        return $stmt->fetchAll();
    }
    
    // Ajouter un fichier à un cours
    public function ajouterFichier($cours_id, $nom_fichier, $nom_original, $chemin_fichier, $taille_fichier, $type_mime) {
        $stmt = $this->pdo->prepare("
            INSERT INTO fichiers_cours (cours_id, nom_fichier, nom_original, chemin_fichier, taille_fichier, type_mime) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$cours_id, $nom_fichier, $nom_original, $chemin_fichier, $taille_fichier, $type_mime]);
    }
    
    // Supprimer un fichier
    public function supprimerFichier($fichier_id) {
        // Récupérer le chemin du fichier avant de le supprimer
        $stmt = $this->pdo->prepare("SELECT chemin_fichier FROM fichiers_cours WHERE id = ?");
        $stmt->execute([$fichier_id]);
        $fichier = $stmt->fetch();
        
        if ($fichier && file_exists($fichier['chemin_fichier'])) {
            unlink($fichier['chemin_fichier']);
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM fichiers_cours WHERE id = ?");
        return $stmt->execute([$fichier_id]);
    }
    
    // Supprimer un cours
    public function supprimerCours($cours_id) {
        // Supprimer tous les fichiers associés
        $fichiers = $this->getFichiersCours($cours_id);
        foreach ($fichiers as $fichier) {
            if (file_exists($fichier['chemin_fichier'])) {
                unlink($fichier['chemin_fichier']);
            }
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM cours WHERE id = ?");
        return $stmt->execute([$cours_id]);
    }
    
    // Formater la taille d'un fichier
    public static function formaterTailleFichier($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
?>

