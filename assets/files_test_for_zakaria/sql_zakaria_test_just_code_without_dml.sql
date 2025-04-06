
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

ALTER TABLE consommations_mensuelles
  ADD CONSTRAINT consommations_mensuelles_ibfk_1 FOREIGN KEY (client_id) REFERENCES clients (client_id) ON DELETE CASCADE;


ALTER TABLE consommation_annuelle
  ADD CONSTRAINT consommation_annuelle_ibfk_1 FOREIGN KEY (client_id) REFERENCES clients (client_id) ON DELETE CASCADE,
  ADD CONSTRAINT consommation_annuelle_ibfk_2 FOREIGN KEY (agent_id) REFERENCES agents (agent_id) ON DELETE CASCADE;


ALTER TABLE dashboard
  ADD CONSTRAINT dashboard_ibfk_1 FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs (fournisseur_id) ON DELETE CASCADE;

ALTER TABLE factures
  ADD CONSTRAINT factures_ibfk_1 FOREIGN KEY (client_id) REFERENCES clients (client_id) ON DELETE CASCADE,
  ADD CONSTRAINT factures_ibfk_2 FOREIGN KEY (consommation_id) REFERENCES consommations_mensuelles (consommation_id) ON DELETE CASCADE;

ALTER TABLE paiements
  ADD CONSTRAINT paiements_ibfk_1 FOREIGN KEY (facture_id) REFERENCES factures (facture_id) ON DELETE CASCADE;
COMMIT;
