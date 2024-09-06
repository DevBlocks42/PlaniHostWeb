CREATE TABLE clients
(
    id INT PRIMARY KEY AUTO_INCREMENT, 
    nom VARCHAR(32),
    prenom VARCHAR(32), 
    email VARCHAR(32),
    tel VARCHAR(32),
    stripe_client_id TEXT DEFAULT NULL
);
CREATE TABLE reservations
(
    id INT PRIMARY KEY AUTO_INCREMENT, 
    nbPersonnes INT,
    idClient INT,
    FOREIGN KEY (idClient) REFERENCES clients(id) ON DELETE CASCADE
);
CREATE TABLE sejours
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    dateDebut DATE,
    dateFin DATE, 
    idReservation INT,
    FOREIGN KEY (idReservation) REFERENCES reservations(id) ON DELETE CASCADE
);
CREATE TABLE chambres
(
    id INT PRIMARY KEY AUTO_INCREMENT, 
    prix FLOAT, 
    capacite INT,
    numero VARCHAR(32), 
    etage INT, 
    titre VARCHAR(128),
    `description` TEXT,
    stripe_prod_id VARCHAR(96),
    images TEXT
);
CREATE TABLE personnes
(
    id INT PRIMARY KEY AUTO_INCREMENT, 
    nom VARCHAR(64),
    prenom VARCHAR(64),
    dateNaissance DATE,
    nationalite VARCHAR(32),
    idChambre INT,
    idSejour INT,
    FOREIGN KEY (idChambre) REFERENCES chambres(id) ON DELETE CASCADE,
    FOREIGN KEY (idSejour) REFERENCES sejours(id) ON DELETE CASCADE
);
CREATE TABLE admins
(
    id INT PRIMARY KEY AUTO_INCREMENT, 
    username VARCHAR(64),
    pwd_hash VARCHAR(255)
);
CREATE TABLE settings
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    clef VARCHAR(255),
    valeur TEXT
);