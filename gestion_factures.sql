-- Création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_factures
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE gestion_factures;

-- Table : fournisseurs
CREATE TABLE fournisseurs (
    fournisseur_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    contact_email VARCHAR(100) NOT NULL,
    contact_telephone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table : clients
CREATE TABLE clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    fournisseur_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(fournisseur_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table : agents
CREATE TABLE agents (
    agent_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table : consommations_mensuelles
CREATE TABLE consommations_mensuelles (
    consommation_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    mois TINYINT NOT NULL CHECK(mois BETWEEN 1 AND 12),
    annee YEAR NOT NULL,
    consommation DECIMAL(10,2) NOT NULL CHECK(consommation >= 0),
    photo_compteur VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (client_id, mois, annee),
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table : factures
CREATE TABLE factures (
    facture_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    consommation_id INT NOT NULL,
    prix_ht DECIMAL(10,2) NOT NULL CHECK(prix_ht >= 0),
    tva DECIMAL(5,2) NOT NULL DEFAULT 18.00,
    prix_ttc DECIMAL(10,2) AS (prix_ht + (prix_ht * tva / 100)) STORED,
    etat ENUM('payee', 'non_payee', 'en_attente') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (consommation_id) REFERENCES consommations_mensuelles(consommation_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table : reclamations
CREATE TABLE reclamations (
    reclamation_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    facture_id INT,
    type_reclamation ENUM('Fuite_externe', 'Fuite_interne', 'Facture', 'Autre') NOT NULL,
    details TEXT,
    statut ENUM('en_cours', 'traitee', 'rejete') DEFAULT 'en_cours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (facture_id) REFERENCES factures(facture_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Table : consommation_annuelle
CREATE TABLE consommation_annuelle (
    consommation_annuelle_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    agent_id INT NOT NULL,
    annee YEAR NOT NULL,
    consommation DECIMAL(10,2) NOT NULL CHECK(consommation >= 0),
    date_saisie DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (client_id, annee),
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES agents(agent_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table : paiements
CREATE TABLE paiements (
    paiement_id INT AUTO_INCREMENT PRIMARY KEY,
    facture_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL CHECK(montant >= 0),
    date_paiement DATE NOT NULL,
    mode_paiement ENUM('Carte', 'Virement', 'Espèces', 'Autre') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (facture_id) REFERENCES factures(facture_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table : dashboard
CREATE TABLE dashboard (
    dashboard_id INT AUTO_INCREMENT PRIMARY KEY,
    fournisseur_id INT NOT NULL,
    montant_factures_non_payees DECIMAL(15,2) NOT NULL,
    consommation_totale_mensuelle DECIMAL(15,2) NOT NULL,
    mois TINYINT NOT NULL CHECK(mois BETWEEN 1 AND 12),
    annee YEAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(fournisseur_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Indexation pour optimiser les performances des jointures
CREATE INDEX idx_client_conso ON consommations_mensuelles (client_id, annee, mois);
CREATE INDEX idx_facture_client ON factures (client_id);
CREATE INDEX idx_reclamation_client ON reclamations (client_id);

-- Trigger : before_insert_facture
DELIMITER $$
CREATE TRIGGER before_insert_facture
BEFORE INSERT ON factures
FOR EACH ROW
BEGIN
    DECLARE cons DECIMAL(10,2);
    SELECT consommation INTO cons FROM consommations_mensuelles
        WHERE consommation_id = NEW.consommation_id;
    
    IF cons IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Consommation introuvable';
    END IF;
    
    IF cons BETWEEN 0 AND 100 THEN
        SET NEW.prix_ht = cons * 0.82;
    ELSEIF cons BETWEEN 101 AND 150 THEN
        SET NEW.prix_ht = cons * 0.92;
    ELSE
        SET NEW.prix_ht = cons * 1.1;
    END IF;
END$$
DELIMITER ;

ALTER TABLE factures CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

