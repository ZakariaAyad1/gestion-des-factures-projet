-- Création de la base de données et sélection de celle-ci
CREATE DATABASE IF NOT EXISTS gestion_factures
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE gestion_factures;

-----------------------------------------------------------
-- Table : clients
-- Cette table stocke les informations uniques de chaque client.
-----------------------------------------------------------
CREATE TABLE clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL, -- stocker le mot de passe sous forme hachée
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-----------------------------------------------------------
-- Table : agents
-- Cette table répertorie les agents qui interviennent pour saisir la consommation annuelle.
-----------------------------------------------------------
CREATE TABLE agents (
    agent_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-----------------------------------------------------------
-- Table : consommations
-- Enregistre la consommation mensuelle saisie par le client.
-- La contrainte UNIQUE (client_id, mois, annee) évite les saisies en double pour un même mois.
-----------------------------------------------------------
CREATE TABLE consommations (
    consommation_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    mois TINYINT NOT NULL CHECK(mois BETWEEN 1 AND 12),
    annee YEAR NOT NULL,
    consommation DECIMAL(10,2) NOT NULL CHECK(consommation >= 0),
    photo_compteur VARCHAR(255) NOT NULL, -- chemin vers la photo du compteur
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (client_id, mois, annee),
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Index pour optimiser les requêtes sur la consommation par client et période
CREATE INDEX idx_client_mois_annee ON consommations (client_id, annee, mois);

-----------------------------------------------------------
-- Table : factures
-- Génère automatiquement la facture à partir de la consommation.
-- Le calcul du prix TTC s’effectue via une colonne générée (STORED).
-----------------------------------------------------------
CREATE TABLE factures (
    facture_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    consommation_id INT NOT NULL, -- référence à la saisie de consommation
    prix_ht DECIMAL(10,2) NOT NULL CHECK(prix_ht >= 0),
    tva DECIMAL(10,2) NOT NULL DEFAULT 18.00, -- TVA de 18%
    -- Colonne générée calculant le montant TTC
    prix_ttc DECIMAL(10,2) AS (prix_ht + (prix_ht * tva / 100)) STORED,
    etat ENUM('payee', 'non_payee', 'en_attente') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (consommation_id) REFERENCES consommations(consommation_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-----------------------------------------------------------
-- Table : reclamations
-- Permet aux clients de faire des réclamations sur leur facture ou leur consommation.
-----------------------------------------------------------
CREATE TABLE reclamations (
    reclamation_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    facture_id INT, -- facultatif : la réclamation peut être liée à une facture
    type_reclamation ENUM('Fuite_externe', 'Fuite_interne', 'Facture', 'Autre') NOT NULL,
    details VARCHAR(255), -- pour spécifier le type 'Autre' ou donner plus d'infos
    statut ENUM('en_cours', 'traitee', 'rejete') DEFAULT 'en_cours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (facture_id) REFERENCES factures(facture_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-----------------------------------------------------------
-- Table : consommation_annuelle
-- Insère la consommation annuelle relevée par un agent.
-- La contrainte UNIQUE sur (client_id, annee) empêche les doublons.
-----------------------------------------------------------
CREATE TABLE consommation_annuelle (
    consommation_annuelle_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    agent_id INT NOT NULL,
    annee YEAR NOT NULL,
    consommation DECIMAL(10,2) NOT NULL CHECK(consommation >= 0),
    date_saisie DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE(client_id, annee),
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES agents(agent_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-----------------------------------------------------------
-- Table : facture_logs
-- Journalisation des modifications effectuées sur les factures.
-----------------------------------------------------------
CREATE TABLE facture_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    facture_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (facture_id) REFERENCES factures(facture_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-----------------------------------------------------------
-- Trigger : facture_update
-- Enregistre dans facture_logs chaque mise à jour d'une facture.
-----------------------------------------------------------
DELIMITER //
CREATE TRIGGER facture_update
AFTER UPDATE ON factures
FOR EACH ROW
BEGIN
    INSERT INTO facture_logs (facture_id, action, action_date)
    VALUES (NEW.facture_id, 'update', NOW());
END;//
DELIMITER ;

-----------------------------------------------------------
-- Gestion des accès
-- Création d'un utilisateur dédié à l'application avec des droits limités.
-----------------------------------------------------------
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'password_strong';
GRANT SELECT, INSERT, UPDATE, DELETE ON gestion_factures.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
