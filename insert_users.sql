-- Script pour ajouter des professeurs et des élèves à la base de données
-- Tous les mots de passe sont : password123

USE gestion_cours;

-- Hash du mot de passe "password123" (généré avec password_hash())
-- Tous les utilisateurs utilisent ce même hash pour le mot de passe "password123"
SET @password_hash = '$2y$12$GtMi9iYUdZbLuwlfAqwLQO1wIN.1YvQPB4jgsXQmAlT8oqT772sGG';

-- Ajout de professeurs
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Dupont', 'Jean', 'jean.dupont@ecole.fr', @password_hash, 'professeur'),
('Martin', 'Marie', 'marie.martin@ecole.fr', @password_hash, 'professeur'),
('Bernard', 'Pierre', 'pierre.bernard@ecole.fr', @password_hash, 'professeur'),
('Dubois', 'Sophie', 'sophie.dubois@ecole.fr', @password_hash, 'professeur'),
('Lefebvre', 'Thomas', 'thomas.lefebvre@ecole.fr', @password_hash, 'professeur');

-- Ajout d'élèves
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Moreau', 'Lucas', 'lucas.moreau@ecole.fr', @password_hash, 'eleve'),
('Laurent', 'Emma', 'emma.laurent@ecole.fr', @password_hash, 'eleve'),
('Simon', 'Léo', 'leo.simon@ecole.fr', @password_hash, 'eleve'),
('Michel', 'Chloé', 'chloe.michel@ecole.fr', @password_hash, 'eleve'),
('Garcia', 'Hugo', 'hugo.garcia@ecole.fr', @password_hash, 'eleve'),
('David', 'Léa', 'lea.david@ecole.fr', @password_hash, 'eleve'),
('Bertrand', 'Nathan', 'nathan.bertrand@ecole.fr', @password_hash, 'eleve'),
('Roux', 'Manon', 'manon.roux@ecole.fr', @password_hash, 'eleve'),
('Vincent', 'Alexandre', 'alexandre.vincent@ecole.fr', @password_hash, 'eleve'),
('Fournier', 'Camille', 'camille.fournier@ecole.fr', @password_hash, 'eleve');

