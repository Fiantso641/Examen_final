-- =====================================================
-- BNGRC - Suivi des collectes & distributions (MySQL)
-- =====================================================

CREATE DATABASE IF NOT EXISTS bngrc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bngrc_db;

CREATE TABLE villes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(120) NOT NULL UNIQUE,
    region VARCHAR(120) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE besoins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ville_id INT NOT NULL,
    type ENUM('nature','materiaux','argent') NOT NULL,
    libelle VARCHAR(160) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    quantite DECIMAL(14,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_besoins_ville FOREIGN KEY (ville_id) REFERENCES villes(id) ON DELETE CASCADE,
    INDEX idx_besoins_fifo (created_at, id)
);

CREATE TABLE dons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('nature','materiaux','argent') NOT NULL,
    libelle VARCHAR(160) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    quantite DECIMAL(14,2) NOT NULL,
    date_don DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_dons_fifo (date_don, id)
);

CREATE TABLE allocations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee DECIMAL(14,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_alloc_don FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE CASCADE,
    CONSTRAINT fk_alloc_besoin FOREIGN KEY (besoin_id) REFERENCES besoins(id) ON DELETE CASCADE,
    INDEX idx_alloc_besoin (besoin_id),
    INDEX idx_alloc_don (don_id)
);

INSERT INTO villes (nom, region) VALUES
('Antananarivo', 'Analamanga'),
('Toamasina', 'Atsinanana'),
('Fianarantsoa', 'Haute Matsiatra');

INSERT INTO besoins (ville_id, type, libelle, prix_unitaire, quantite) VALUES
(1, 'nature', 'riz', 2500, 300),
(1, 'nature', 'huile', 9000, 40),
(2, 'materiaux', 'tole', 35000, 20),
(3, 'argent', 'aide', 1, 500000);

INSERT INTO dons (type, libelle, prix_unitaire, quantite, date_don) VALUES
('nature', 'riz', 2500, 150, '2026-02-16'),
('nature', 'huile', 9000, 20, '2026-02-16'),
('argent', 'aide', 1, 200000, '2026-02-16'),
('materiaux', 'tole', 35000, 10, '2026-02-17');
