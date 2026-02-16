-- Création de la base de données
CREATE DATABASE IF NOT EXISTS livraison_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE livraison_db;

-- Table des chauffeurs
CREATE TABLE chauffeurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    salaire_par_livraison DECIMAL(10,2) NOT NULL
);

-- Table des véhicules
CREATE TABLE vehicules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    immatriculation VARCHAR(20) UNIQUE NOT NULL,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    cout_par_livraison DECIMAL(10,2) NOT NULL
);

-- Table des zones de livraison (NOUVEAU)
CREATE TABLE zones_livraison (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_zone VARCHAR(100) NOT NULL UNIQUE,
    supplement_pourcentage DECIMAL(5,2) NOT NULL DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des colis
CREATE TABLE colis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reference VARCHAR(50) UNIQUE NOT NULL,
    poids_kg DECIMAL(10,2) NOT NULL,
    prix_par_kg DECIMAL(10,2) NOT NULL DEFAULT 5000
);

-- Table des livraisons (MODIFIÉE)
CREATE TABLE livraisons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    colis_id INT NOT NULL,
    chauffeur_id INT NOT NULL,
    vehicule_id INT NOT NULL,
    zone_id INT NOT NULL,
    
    adresse_destination TEXT NOT NULL,
    
    -- Coûts saisis par livraison
    cout_vehicule DECIMAL(10,2) NOT NULL,
    salaire_chauffeur DECIMAL(10,2) NOT NULL,
    
    -- Chiffre d'affaires de base (poids_kg * prix_par_kg)
    chiffre_affaire_base DECIMAL(10,2) NOT NULL,
    
    -- Supplément de zone (calculé)
    supplement_zone DECIMAL(10,2) NOT NULL DEFAULT 0,
    
    -- Chiffre d'affaires total (base + supplément)
    chiffre_affaire_total DECIMAL(10,2) AS (chiffre_affaire_base + supplement_zone) STORED,
    
    -- Bénéfice calculé automatiquement
    benefice DECIMAL(10,2) AS (chiffre_affaire_base + supplement_zone - cout_vehicule - salaire_chauffeur) STORED,
    
    statut ENUM('en_attente', 'livre', 'annule') DEFAULT 'en_attente',
    date_livraison DATE NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (colis_id) REFERENCES colis(id),
    FOREIGN KEY (chauffeur_id) REFERENCES chauffeurs(id),
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id),
    FOREIGN KEY (zone_id) REFERENCES zones_livraison(id)
);

-- =====================================================
-- DONNÉES DE TEST
-- =====================================================

-- 12 Chauffeurs avec salaires variés
INSERT INTO chauffeurs (nom, prenom, telephone, salaire_par_livraison) VALUES
('RAKOTO', 'Jean', '032 12 345 67', 15000),
('RABE', 'Paul', '033 23 456 78', 15000),
('RAZAFY', 'Pierre', '034 34 567 89', 15000),
('RANDRIA', 'Michel', '032 45 678 90', 15000),
('RASOLOFO', 'Luc', '033 56 789 01', 15000),
('RANDRIANASOLO', 'Marc', '034 67 890 12', 18000),
('RABEARIVELO', 'André', '032 78 901 23', 18000),
('RAHARISON', 'Jacques', '033 89 012 34', 18000),
('RAKOTONDRABE', 'Henri', '034 90 123 45', 20000),
('RASOANAIVO', 'François', '032 01 234 56', 20000),
('RAJAONARISON', 'David', '033 12 345 67', 20000),
('RAFALIMANANA', 'Simon', '034 23 456 78', 20000);

-- 10 Véhicules
INSERT INTO vehicules (immatriculation, marque, modele, cout_par_livraison) VALUES
('1234 TAC', 'Toyota', 'Hilux', 15000),
('5678 TBB', 'Isuzu', 'D-Max', 18000),
('9012 TBC', 'Mitsubishi', 'L200', 16000),
('3456 TBD', 'Ford', 'Ranger', 17000),
('7890 TBE', 'Nissan', 'Navara', 15500),
('1122 TBF', 'Toyota', 'Land Cruiser', 20000),
('3344 TBG', 'Mazda', 'BT-50', 16500),
('5566 TBH', 'Chevrolet', 'Colorado', 17500),
('7788 TBI', 'Volkswagen', 'Amarok', 18500),
('9900 TBJ', 'Mercedes', 'X-Class', 22000);

-- 5 Zones de livraison (3 à 12.5%, 2 à 0%)
INSERT INTO zones_livraison (nom_zone, supplement_pourcentage, description) VALUES
('Zone Centre', 12.5, 'Antananarivo centre-ville - Analakely, Ankorondrano'),
('Zone Périphérie', 12.5, 'Périphérie Antananarivo - Ivato, Ambohimanarina'),
('Zone Province', 12.5, 'Grandes villes - Toamasina, Mahajanga, Fianarantsoa'),
('Zone Locale', 0, 'Livraison locale sans supplément'),
('Zone Gratuite', 0, 'Zone promotionnelle sans supplément');

-- Colis
INSERT INTO colis (reference, poids_kg, prix_par_kg) VALUES
('COL-001', 15.5, 8000),
('COL-002', 8.2, 6000),
('COL-003', 25.0, 7000),
('COL-004', 12.3, 5500),
('COL-005', 18.7, 6500),
('COL-006', 10.0, 7500),
('COL-007', 20.5, 6800),
('COL-008', 14.2, 7200);

-- Livraisons avec zones et suppléments
INSERT INTO livraisons 
    (colis_id, chauffeur_id, vehicule_id, zone_id, adresse_destination, 
     cout_vehicule, salaire_chauffeur, chiffre_affaire_base, supplement_zone, statut, date_livraison) 
VALUES
-- Décembre 2025
(1, 1, 1, 1, 'Analakely, Antananarivo', 15000, 15000, 124000, 15500, 'livre', '2025-12-01'),
(2, 2, 2, 1, 'Ankorondrano, Antananarivo', 18000, 15000, 49200, 6150, 'livre', '2025-12-05'),
(3, 3, 3, 2, 'Ivato, Antananarivo', 16000, 15000, 175000, 21875, 'en_attente', '2025-12-16'),
(4, 4, 4, 4, 'Ambohijanahary', 17000, 15000, 67650, 0, 'livre', '2025-12-12'),

-- Novembre 2025
(5, 5, 5, 3, 'Toamasina', 15500, 18000, 121550, 15193.75, 'livre', '2025-11-15'),
(6, 6, 6, 3, 'Mahajanga', 20000, 18000, 75000, 9375, 'livre', '2025-11-20'),
(7, 7, 7, 5, 'Antsirabe', 16500, 18000, 140400, 0, 'livre', '2025-11-25'),

-- Octobre 2025
(8, 8, 8, 1, 'Fianarantsoa', 17500, 20000, 102240, 12780, 'livre', '2025-10-10'),
(1, 9, 9, 2, 'Behoririka', 18500, 20000, 124000, 15500, 'annule', '2025-10-22'),
(2, 10, 10, 4, 'Andohalo', 22000, 20000, 49200, 0, 'livre', '2025-10-28');

-- =====================================================
-- VUES POUR LES BÉNÉFICES (MISES À JOUR)
-- =====================================================

-- Bénéfices par jour
CREATE VIEW v_benefices_jour AS
SELECT 
    date_livraison AS jour,
    COUNT(*) AS nb_livraisons,
    SUM(CASE WHEN statut = 'livre' THEN chiffre_affaire_total ELSE 0 END) AS ca_total,
    SUM(CASE WHEN statut = 'livre' THEN (cout_vehicule + salaire_chauffeur) ELSE 0 END) AS cout_total,
    SUM(CASE WHEN statut = 'livre' THEN benefice ELSE 0 END) AS benefice_total
FROM livraisons
GROUP BY date_livraison
ORDER BY date_livraison DESC;

-- Bénéfices par mois
CREATE VIEW v_benefices_mois AS
SELECT 
    DATE_FORMAT(date_livraison, '%Y-%m') AS mois,
    COUNT(*) AS nb_livraisons,
    SUM(CASE WHEN statut = 'livre' THEN chiffre_affaire_total ELSE 0 END) AS ca_total,
    SUM(CASE WHEN statut = 'livre' THEN (cout_vehicule + salaire_chauffeur) ELSE 0 END) AS cout_total,
    SUM(CASE WHEN statut = 'livre' THEN benefice ELSE 0 END) AS benefice_total
FROM livraisons
GROUP BY DATE_FORMAT(date_livraison, '%Y-%m')
ORDER BY mois DESC;

-- Bénéfices par année
CREATE VIEW v_benefices_annee AS
SELECT 
    YEAR(date_livraison) AS annee,
    COUNT(*) AS nb_livraisons,
    SUM(CASE WHEN statut = 'livre' THEN chiffre_affaire_total ELSE 0 END) AS ca_total,
    SUM(CASE WHEN statut = 'livre' THEN (cout_vehicule + salaire_chauffeur) ELSE 0 END) AS cout_total,
    SUM(CASE WHEN statut = 'livre' THEN benefice ELSE 0 END) AS benefice_total
FROM livraisons
GROUP BY YEAR(date_livraison)
ORDER BY annee DESC;

-- Vue pour analyser les zones
CREATE VIEW v_analyse_zones AS
SELECT 
    z.nom_zone,
    z.supplement_pourcentage,
    COUNT(l.id) AS nb_livraisons,
    SUM(CASE WHEN l.statut = 'livre' THEN l.chiffre_affaire_total ELSE 0 END) AS ca_total,
    SUM(CASE WHEN l.statut = 'livre' THEN l.supplement_zone ELSE 0 END) AS supplement_total,
    SUM(CASE WHEN l.statut = 'livre' THEN l.benefice ELSE 0 END) AS benefice_total
FROM zones_livraison z
LEFT JOIN livraisons l ON z.id = l.zone_id
GROUP BY z.id, z.nom_zone, z.supplement_pourcentage
ORDER BY nb_livraisons DESC;