-- =====================================================
-- Takalo-takalo (MySQL) : Schema + Donnees de test
-- =====================================================

CREATE DATABASE IF NOT EXISTS takalo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE takalo_db;

-- ==========================
-- AUTH
-- ==========================

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ==========================
-- CATALOGUE
-- ==========================

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(120) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE objets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    categorie_id INT NOT NULL,
    titre VARCHAR(160) NOT NULL,
    description TEXT,
    prix_estime DECIMAL(12,2) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_objets_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_objets_categorie FOREIGN KEY (categorie_id) REFERENCES categories(id)
);

CREATE TABLE objet_photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_objet_photos_objet FOREIGN KEY (objet_id) REFERENCES objets(id) ON DELETE CASCADE
);

-- ==========================
-- ECHANGES
-- ==========================

CREATE TABLE echanges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_demande_id INT NOT NULL,
    objet_propose_id INT NOT NULL,
    proposer_user_id INT NOT NULL,
    proprietaire_user_id INT NOT NULL,
    statut ENUM('propose','accepte','refuse','annule') NOT NULL DEFAULT 'propose',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    decided_at DATETIME NULL,
    CONSTRAINT fk_echanges_demande FOREIGN KEY (objet_demande_id) REFERENCES objets(id),
    CONSTRAINT fk_echanges_propose FOREIGN KEY (objet_propose_id) REFERENCES objets(id),
    CONSTRAINT fk_echanges_proposer FOREIGN KEY (proposer_user_id) REFERENCES users(id),
    CONSTRAINT fk_echanges_proprietaire FOREIGN KEY (proprietaire_user_id) REFERENCES users(id)
);

-- Historique des proprietaires (visible au public)
CREATE TABLE objet_ownership_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_id INT NOT NULL,
    user_id INT NOT NULL,
    acquired_at DATETIME NOT NULL,
    echange_id INT NULL,
    CONSTRAINT fk_hist_objet FOREIGN KEY (objet_id) REFERENCES objets(id) ON DELETE CASCADE,
    CONSTRAINT fk_hist_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_hist_echange FOREIGN KEY (echange_id) REFERENCES echanges(id) ON DELETE SET NULL,
    INDEX idx_hist_objet_date (objet_id, acquired_at)
);

-- ==========================
-- DONNEES DE TEST
-- ==========================

-- Login admin: admin / admin123
INSERT INTO admins (username, password) VALUES
('admin', 'admin123');

-- Users (password: user123)
INSERT INTO users (nom, prenom, email, password) VALUES
('RAKOTO', 'Jean', 'jean@example.com', 'user123'),
('RABE', 'Sara', 'sara@example.com', 'user123'),
('RAZAFY', 'Paul', 'paul@example.com', 'user123');

INSERT INTO categories (nom) VALUES
('Vetements'),
('Livres'),
('DVD'),
('Jeux');

INSERT INTO objets (user_id, categorie_id, titre, description, prix_estime) VALUES
(1, 1, 'Veste en jean', 'Taille M, bon etat', 45000),
(1, 2, 'Roman policier', 'Livre presque neuf', 20000),
(2, 3, 'DVD Film', 'Edition collector', 30000),
(2, 4, 'Jeu de societe', 'Complet', 60000),
(3, 2, 'Livre cuisine', 'Recettes malgaches', 25000);

INSERT INTO objet_ownership_history (objet_id, user_id, acquired_at, echange_id) VALUES
(1, 1, NOW(), NULL),
(2, 1, NOW(), NULL),
(3, 2, NOW(), NULL),
(4, 2, NOW(), NULL),
(5, 3, NOW(), NULL);

-- Exemples de propositions d'echange
INSERT INTO echanges (objet_demande_id, objet_propose_id, proposer_user_id, proprietaire_user_id, statut, decided_at) VALUES
(3, 1, 1, 2, 'propose', NULL),
(2, 4, 2, 1, 'refuse', NOW());
