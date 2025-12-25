-- Base de données pour le système de gestion de cours
CREATE DATABASE IF NOT EXISTS gestion_cours CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gestion_cours;

-- Table des utilisateurs (professeurs et élèves)
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('professeur', 'eleve') NOT NULL DEFAULT 'eleve',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des cours
CREATE TABLE IF NOT EXISTS cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    professeur_id INT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (professeur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des fichiers de cours
CREATE TABLE IF NOT EXISTS fichiers_cours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cours_id INT NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    nom_original VARCHAR(255) NOT NULL,
    chemin_fichier VARCHAR(500) NOT NULL,
    taille_fichier INT NOT NULL,
    type_mime VARCHAR(100),
    date_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison cours-élèves (pour suivre quels élèves ont accès à quels cours)
CREATE TABLE IF NOT EXISTS cours_eleves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cours_id INT NOT NULL,
    eleve_id INT NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
    FOREIGN KEY (eleve_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cours_eleve (cours_id, eleve_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion d'un utilisateur administrateur par défaut (mot de passe: admin123)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Admin', 'Professeur', 'admin@ecole.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'professeur');

