-- Nettoyage des données existantes pour éviter les erreurs de clés uniques
DELETE FROM prestataire_entrepreneur;
DELETE FROM entrepreneur_categorie;
DELETE FROM prestataire;
DELETE FROM categorie;
DELETE FROM bien;
DELETE FROM entrepreneur;
DELETE FROM inspecteur;
DELETE FROM utilisateur;

-- 1. UTILISATEURS (Hash du mot de passe 'toto')
-- Ordre : email, roles, password, nom, prenom, telephone, adresse, ville, code_postal
INSERT INTO `utilisateur` (`email`, `roles`, `password`, `nom`, `prenom`, `telephone`, `adresse`, `ville`, `code_postal`) VALUES
('admin@immosync.fr', '["ROLE_ADMIN"]', '$2y$13$E6.87w99oXG7uK.p9Kx8AeXo40M6a9X7h9k8Y1.8Y1.8Y1.8Y1.8Y', 'Admin', 'ImmoSync', '0100000000', '1 Rue de la Gestion', 'Paris', '75001'),
('m.veron@immosync.fr', '["ROLE_INSPECTEUR"]', '$2y$13$E6.87w99oXG7uK.p9Kx8AeXo40M6a9X7h9k8Y1.8Y1.8Y1.8Y1.8Y', 'Veron', 'Michel', '0612345678', '12 Avenue des Experts', 'Lyon', '69002'),
('j.dubois@immosync.fr', '["ROLE_INSPECTEUR"]', '$2y$13$E6.87w99oXG7uK.p9Kx8AeXo40M6a9X7h9k8Y1.8Y1.8Y1.8Y1.8Y', 'Dubois', 'Julie', '0623456789', '45 Boulevard du Contrôle', 'Bordeaux', '33000'),
('lucas.martin@gmail.com', '["ROLE_USER"]', '$2y$13$E6.87w99oXG7uK.p9Kx8AeXo40M6a9X7h9k8Y1.8Y1.8Y1.8Y1.8Y', 'Martin', 'Lucas', '0640506070', '8 Rue de la Paix', 'Paris', '75002'),
('emma.bernard@yahoo.fr', '["ROLE_USER"]', '$2y$13$E6.87w99oXG7uK.p9Kx8AeXo40M6a9X7h9k8Y1.8Y1.8Y1.8Y1.8Y', 'Bernard', 'Emma', '0789451236', '14 Avenue Jean Jaurès', 'Lyon', '69007'),
('contact@bati-expert.fr', '["ROLE_ENTREPRENEUR"]', '$2y$13$E6.87w99oXG7uK.p9Kx8AeXo40M6a9X7h9k8Y1.8Y1.8Y1.8Y1.8Y', 'BatiExpert', 'SARL', '0145859632', '25 Rue de l\'Artisanat', 'Nanterre', '92000'),
('devis@elec-pro.com', '["ROLE_ENTREPRENEUR"]', '$2y$13$E6.87w99oXG7uK.p9Kx8AeXo40M6a9X7h9k8Y1.8Y1.8Y1.8Y1.8Y', 'ElecPro', 'Solutions', '0478956231', '3 Cours Lafayette', 'Lyon', '69003');

-- 2. INSPECTEURS
INSERT INTO `inspecteur` (`id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `ville`, `code_postal`) VALUES
(1, 'Veron', 'Michel', 'm.veron@immosync.fr', '0612345678', '12 Avenue des Experts', 'Lyon', '69002'),
(2, 'Dubois', 'Julie', 'j.dubois@immosync.fr', '0623456789', '45 Boulevard du Contrôle', 'Bordeaux', '33000');

-- 3. ENTREPRENEURS
INSERT INTO `entrepreneur` (`id`, `nom`, `siret`, `email`, `telephone`, `adresse`, `ville`, `code_postal`) VALUES
(1, 'BatiExpert SARL', '12345678900012', 'contact@bati-expert.fr', '0145859632', '25 Rue de l\'Artisanat', 'Nanterre', '92000'),
(2, 'ElecPro Solutions', '98765432100055', 'devis@elec-pro.com', '0478956231', '3 Cours Lafayette', 'Lyon', '69003');

-- 4. BIENS IMMOBILIERS
INSERT INTO `bien` (`adresse`, `ville`, `code_postal`, `surface`, `utilisateur_id`) VALUES
('15 Rue de Rivoli', 'Paris', '75004', 65.00, 4),
('30 Avenue de la Toison d\'Or', 'Lyon', '69006', 110.50, 4),
('2 bis Place de la Comédie', 'Bordeaux', '33000', 42.00, 5);

-- 5. CATÉGORIES
INSERT INTO `categorie` (`id`, `libelle`) VALUES 
(1, 'Gros Œuvre'), 
(2, 'Plomberie'), 
(3, 'Électricité'), 
(4, 'Revêtements'), 
(5, 'Menuiserie');

-- 6. PRESTATIONS (PRESTATAIRE)
INSERT INTO `prestataire` (`id`, `libelle`, `description`, `prix_base`, `categorie_id`) VALUES
(1, 'Démolition de cloison', 'Démolition manuelle et évacuation des gravats', 120.00, 1),
(2, 'Installation mitigeur', 'Pose et raccordement évier/vasque', 145.00, 2),
(3, 'Remplacement chauffe-eau', 'Ballon 200L thermodynamique', 850.00, 2),
(4, 'Installation tableau', 'Tableau 2 rangées aux normes NF C 15-100', 1100.00, 3),
(5, 'Point lumineux', 'Création d\'un point d\'éclairage complet', 85.00, 3),
(6, 'Peinture murale', 'Lessivage, enduit et 2 couches de finition mate', 35.00, 4),
(7, 'Pose de carrelage', 'Pose droite hors fourniture carreaux', 55.00, 4),
(8, 'Pose fenêtre PVC', 'Double vitrage standard 120x100', 450.00, 5);

-- 7. LIAISONS ENTREPRENEURS / CATÉGORIES
INSERT INTO `entrepreneur_categorie` (`entrepreneur_id`, `categorie_id`) VALUES
(1, 1), (1, 4), (1, 5),
(2, 2), (2, 3);

-- 8. LIAISONS PRESTATIONS / ENTREPRENEURS
INSERT INTO `prestataire_entrepreneur` (`prestataire_id`, `entrepreneur_id`) VALUES
(1, 1), (6, 1), (7, 1), (8, 1),
(2, 2), (3, 2), (4, 2), (5, 2);
