PRAGMA foreign_keys = ON;

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    prefixe INTEGER NOT NULL UNIQUE
);

CREATE TABLE type_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operations INTEGER NOT NULL,
    tranche_min INTEGER NOT NULL,
    tranche_max INTEGER NOT NULL,
    montant_frais DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_type_operations) REFERENCES type_operations(id),
    CHECK (tranche_min >= 0),
    CHECK (tranche_max >= tranche_min),
    CHECK (montant_frais >= 0)
);

CREATE TABLE client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE compte_client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client INTEGER NOT NULL UNIQUE,
    date_creation DATE NOT NULL DEFAULT CURRENT_DATE,
    solde DECIMAL(12, 2) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_client) REFERENCES client(id),
    CHECK (solde >= 0)
);

CREATE TABLE "transaction" (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operations INTEGER NOT NULL,
    montant DECIMAL(12, 2) NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    id_compte_client INTEGER NOT NULL,
    id_compte_destinataire INTEGER,
    montant_frais DECIMAL(10, 2) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_type_operations) REFERENCES type_operations(id),
    FOREIGN KEY (id_compte_client) REFERENCES compte_client(id),
    FOREIGN KEY (id_compte_destinataire) REFERENCES compte_client(id),
    CHECK (montant > 0),
    CHECK (montant_frais >= 0)
);

CREATE TABLE historique_transaction (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_transaction INTEGER NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    montant DECIMAL(12, 2) NOT NULL,
    id_type_operations INTEGER NOT NULL,
    solde_avant DECIMAL(12, 2) NOT NULL,
    solde_apres DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (id_transaction) REFERENCES "transaction"(id),
    FOREIGN KEY (id_type_operations) REFERENCES type_operations(id),
    CHECK (montant > 0),
    CHECK (solde_avant >= 0),
    CHECK (solde_apres >= 0)
);

INSERT INTO operateur (id, nom, prefixe) VALUES
    (1, 'Telma MVola', 34),
    (2, 'Airtel Money', 33),
    (3, 'Orange Money', 32),
    (4, 'Yas Money', 38);

INSERT INTO type_operations (id, nom) VALUES
    (1, 'Depot'),
    (2, 'Retrait'),
    (3, 'Transfert');

INSERT INTO frais (id, id_type_operations, tranche_min, tranche_max, montant_frais) VALUES
    (1, 1, 0, 10000, 0),
    (2, 1, 10001, 50000, 200),
    (3, 1, 50001, 200000, 500),
    (4, 2, 0, 10000, 300),
    (5, 2, 10001, 50000, 700),
    (6, 2, 50001, 200000, 1500),
    (7, 3, 0, 10000, 200),
    (8, 3, 10001, 50000, 500),
    (9, 3, 50001, 200000, 1200);

INSERT INTO client (id, nom, prenom, date_naissance, adresse, email, telephone) VALUES
    (1, 'Rakoto', 'Andry', '1998-04-12', 'Analakely, Antananarivo', 'andry.rakoto@example.com', '0341234567'),
    (2, 'Rabe', 'Miora', '2001-09-25', 'Ankorondrano, Antananarivo', 'miora.rabe@example.com', '0337654321'),
    (3, 'Randria', 'Hery', '1995-01-08', 'Toamasina Centre', 'hery.randria@example.com', '0329876543'),
    (4, 'Rasolonirina', 'Fanja', '1999-11-19', 'Mahamasina, Antananarivo', 'fanja.raso@example.com', '0381122334');

INSERT INTO compte_client (id, id_client, date_creation, solde) VALUES
    (1, 1, '2026-06-01', 85000),
    (2, 2, '2026-06-03', 45000),
    (3, 3, '2026-06-07', 125000),
    (4, 4, '2026-06-10', 10000);

INSERT INTO "transaction" (
    id,
    id_type_operations,
    montant,
    date,
    id_compte_client,
    id_compte_destinataire,
    montant_frais
) VALUES
    (1, 1, 50000, '2026-06-11', 1, NULL, 200),
    (2, 2, 15000, '2026-06-12', 2, NULL, 700),
    (3, 3, 25000, '2026-06-13', 1, 2, 500),
    (4, 1, 100000, '2026-06-14', 3, NULL, 500),
    (5, 2, 5000, '2026-06-15', 4, NULL, 300);

INSERT INTO historique_transaction (
    id,
    id_transaction,
    date,
    montant,
    id_type_operations,
    solde_avant,
    solde_apres
) VALUES
    (1, 1, '2026-06-11', 50000, 1, 35000, 85000),
    (2, 2, '2026-06-12', 15000, 2, 60700, 45000),
    (3, 3, '2026-06-13', 25000, 3, 110500, 85000),
    (4, 3, '2026-06-13', 25000, 3, 20000, 45000),
    (5, 4, '2026-06-14', 100000, 1, 25000, 125000),
    (6, 5, '2026-06-15', 5000, 2, 15300, 10000);
