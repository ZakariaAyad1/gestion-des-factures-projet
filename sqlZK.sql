-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 05 avr. 2025 à 16:08
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : gestion_factures
--

-- --------------------------------------------------------

--
-- Structure de la table agents
--

DROP TABLE IF EXISTS agents;
CREATE TABLE IF NOT EXISTS agents (
  agent_id int NOT NULL AUTO_INCREMENT,
  nom varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  prenom varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  mot_de_passe varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (agent_id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table clients
--

DROP TABLE IF EXISTS clients;
CREATE TABLE IF NOT EXISTS clients (
  client_id int NOT NULL AUTO_INCREMENT,
  nom varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  prenom varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  adresse varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  mot_de_passe varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (client_id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table clients
--

INSERT INTO clients (client_id, nom, prenom, adresse, email, mot_de_passe, created_at, updated_at) VALUES
(1, 'El Amrani', 'Ahmed', '12 Rue Mohammed V, Casablanca', 'ahmed.elamrani@email.ma', 'Ahmed123', '2025-04-01 00:36:51', '2025-04-01 00:36:51'),
(2, 'Benjelloun', 'Fatima', '45 Avenue Hassan II, Rabat', 'fatima.benjelloun@email.ma', 'Fatima456', '2025-04-01 00:36:51', '2025-04-01 00:36:51'),
(3, 'Cherkaoui', 'Youssef', '8 Boulevard Mohammed VI, Marrakech', 'youssef.cherkaoui@email.ma', 'Youssef789', '2025-04-01 00:36:51', '2025-04-01 00:36:51');

-- --------------------------------------------------------

--
-- Structure de la table consommations_mensuelles
--

DROP TABLE IF EXISTS consommations_mensuelles;
CREATE TABLE IF NOT EXISTS consommations_mensuelles (
  consommation_id int NOT NULL AUTO_INCREMENT,
  client_id int NOT NULL,
  mois tinyint NOT NULL,
  annee year NOT NULL,
  consommation decimal(10,2) NOT NULL,
  photo_compteur varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (consommation_id),
  UNIQUE KEY client_id (client_id,mois,annee),
  KEY idx_client_conso (client_id,annee,mois)
) ;

--
-- Déchargement des données de la table consommations_mensuelles
--

INSERT INTO consommations_mensuelles (consommation_id, client_id, mois, annee, consommation, photo_compteur, created_at, updated_at) VALUES
(1, 1, 1, '2024', 250.00, 'photo_janvier.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(2, 1, 2, '2024', 270.00, 'photo_fevrier.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(3, 1, 3, '2024', 260.00, 'photo_mars.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(4, 1, 4, '2024', 280.00, 'photo_avril.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(5, 1, 5, '2024', 300.00, 'photo_mai.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(6, 1, 6, '2024', 310.00, 'photo_juin.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(7, 1, 7, '2024', 290.00, 'photo_juillet.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(8, 1, 8, '2024', 320.00, 'photo_aout.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35'),
(9, 1, 9, '2024', 330.00, 'photo_septembre.jpg', '2025-04-02 23:05:35', '2025-04-02 23:05:35');

-- --------------------------------------------------------

--
-- Structure de la table consommation_annuelle
--

DROP TABLE IF EXISTS consommation_annuelle;
CREATE TABLE IF NOT EXISTS consommation_annuelle (
  consommation_annuelle_id int NOT NULL AUTO_INCREMENT,
  client_id int NOT NULL,
  agent_id int NOT NULL,
  annee year NOT NULL,
  consommation decimal(10,2) NOT NULL,
  date_saisie date NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (consommation_annuelle_id),
  UNIQUE KEY client_id (client_id,annee),
  KEY agent_id (agent_id)
) ;

-- --------------------------------------------------------

--
-- Structure de la table dashboard
--

DROP TABLE IF EXISTS dashboard;
CREATE TABLE IF NOT EXISTS dashboard (
  dashboard_id int NOT NULL AUTO_INCREMENT,
  fournisseur_id int NOT NULL,
  montant_factures_non_payees decimal(15,2) NOT NULL,
  consommation_totale_mensuelle decimal(15,2) NOT NULL,
  mois tinyint NOT NULL,
  annee year NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (dashboard_id),
  KEY fournisseur_id (fournisseur_id)
) ;

-- --------------------------------------------------------

--
-- Structure de la table factures
--

DROP TABLE IF EXISTS factures;
CREATE TABLE IF NOT EXISTS factures (
  facture_id int NOT NULL AUTO_INCREMENT,
  client_id int NOT NULL,
  consommation_id int NOT NULL,
  prix_ht decimal(10,2) NOT NULL,
  tva decimal(5,2) NOT NULL DEFAULT '18.00',
  prix_ttc decimal(10,2) GENERATED ALWAYS AS ((prix_ht + ((prix_ht * tva) / 100))) STORED,
  etat enum('payee','non_payee','en_attente') COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (facture_id),
  KEY consommation_id (consommation_id),
  KEY idx_facture_client (client_id)
) ;

--
-- Déchargement des données de la table factures
--

INSERT INTO factures (facture_id, client_id, consommation_id, prix_ht, tva, etat, created_at, updated_at) VALUES
(19, 1, 1, 275.00, 18.00, 'payee', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(20, 1, 2, 297.00, 18.00, 'non_payee', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(21, 1, 3, 286.00, 18.00, 'en_attente', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(22, 1, 4, 308.00, 18.00, 'payee', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(23, 1, 5, 330.00, 18.00, 'non_payee', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(24, 1, 6, 341.00, 18.00, 'en_attente', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(25, 1, 7, 319.00, 18.00, 'payee', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(26, 1, 8, 352.00, 18.00, 'non_payee', '2025-04-02 23:46:49', '2025-04-02 23:46:49'),
(27, 1, 9, 363.00, 18.00, 'en_attente', '2025-04-02 23:46:49', '2025-04-02 23:46:49');

--
-- Déclencheurs factures
--
DROP TRIGGER IF EXISTS before_insert_facture;
DELIMITER $$
CREATE TRIGGER before_insert_facture BEFORE INSERT ON factures FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table fournisseurs
--

DROP TABLE IF EXISTS fournisseurs;
CREATE TABLE IF NOT EXISTS fournisseurs (
  fournisseur_id int NOT NULL AUTO_INCREMENT,
  nom varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  adresse varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_email varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_telephone varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (fournisseur_id)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table paiements
--

DROP TABLE IF EXISTS paiements;
CREATE TABLE IF NOT EXISTS paiements (
  paiement_id int NOT NULL AUTO_INCREMENT,
  facture_id int NOT NULL,
  montant decimal(10,2) NOT NULL,
  date_paiement date NOT NULL,
  mode_paiement enum('Carte','Virement','Espèces','Autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (paiement_id),
  KEY facture_id (facture_id)
) ;

-- --------------------------------------------------------

--
-- Structure de la table reclamations
--

DROP TABLE IF EXISTS reclamations;
CREATE TABLE IF NOT EXISTS reclamations (
  id int NOT NULL AUTO_INCREMENT,
  client_id int NOT NULL,
  type varchar(100) NOT NULL,
  description text NOT NULL,
  statut enum('En cours','Resolu') DEFAULT 'En cours',
  reponse text,
  piece_jointe varchar(255) DEFAULT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY client_id (client_id)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table reclamations
--

INSERT INTO reclamations (id, client_id, type, description, statut, reponse, piece_jointe, created_at, updated_at) VALUES
(1, 1, 'Demande', 'Modification adresse facturation', 'Resolu', NULL, 'piece_jointe1.pdf', '2025-04-05 03:49:55', '2025-04-05 05:51:15'),
(2, 1, 'Réclamation', 'Facture erronée', 'En cours', 'Facture corrigée envoyée', 'facture_correction.pdf', '2025-04-01 10:00:00', '2025-04-05 05:51:21'),
(3, 2, 'Question', 'Délai de paiement', 'En cours', 'En attente de réponse du service financier', NULL, '2025-04-05 09:15:00', '2025-04-05 09:15:00'),
(4, 3, 'Demande', 'Changement mode de paiement', 'Resolu', 'Mode de paiement mis à jour', 'autorisation_prelevement.pdf', '2025-03-28 14:20:00', '2025-04-05 05:51:24'),
(5, 2, 'Réclamation', 'Service non fourni', 'En cours', NULL, 'preuve_service.pdf', '2025-04-04 16:30:00', '2025-04-05 05:51:33');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table consommations_mensuelles
--
ALTER TABLE consommations_mensuelles
  ADD CONSTRAINT consommations_mensuelles_ibfk_1 FOREIGN KEY (client_id) REFERENCES clients (client_id) ON DELETE CASCADE;

--
-- Contraintes pour la table consommation_annuelle
--
ALTER TABLE consommation_annuelle
  ADD CONSTRAINT consommation_annuelle_ibfk_1 FOREIGN KEY (client_id) REFERENCES clients (client_id) ON DELETE CASCADE,
  ADD CONSTRAINT consommation_annuelle_ibfk_2 FOREIGN KEY (agent_id) REFERENCES agents (agent_id) ON DELETE CASCADE;

--
-- Contraintes pour la table dashboard
--
ALTER TABLE dashboard
  ADD CONSTRAINT dashboard_ibfk_1 FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs (fournisseur_id) ON DELETE CASCADE;

--
-- Contraintes pour la table factures
--
ALTER TABLE factures
  ADD CONSTRAINT factures_ibfk_1 FOREIGN KEY (client_id) REFERENCES clients (client_id) ON DELETE CASCADE,
  ADD CONSTRAINT factures_ibfk_2 FOREIGN KEY (consommation_id) REFERENCES consommations_mensuelles (consommation_id) ON DELETE CASCADE;

--
-- Contraintes pour la table paiements
--
ALTER TABLE paiements
  ADD CONSTRAINT paiements_ibfk_1 FOREIGN KEY (facture_id) REFERENCES factures (facture_id) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;