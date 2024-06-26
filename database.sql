CREATE DATABASE IF NOT EXISTS Edunote;
USE Edunote;

-- Création de la table CLASSE
CREATE TABLE CLASSE (
    ID_classe INT PRIMARY KEY,
    nom VARCHAR(255)
);

-- Insertion d'une classe
INSERT INTO CLASSE (ID_classe, nom) VALUES 
(2, 'TPA'),
(3, 'TPB'),
(4, 'Test');


-- Création de la table ADMINISTRATEUR
CREATE TABLE ADMINISTRATEUR(
    ID_admin VARCHAR(255) PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    adresse_mail VARCHAR(255),
    password VARCHAR(255)
);

-- Création de la table UE
CREATE TABLE UE (
    ID_UE VARCHAR(255) PRIMARY KEY,
    Competence VARCHAR(255)
);

-- Création de la table TYPE_ENSEIGNEMENT
CREATE TABLE TYPE_ENSEIGNEMENT(
    type VARCHAR(255) PRIMARY KEY
);

-- Création de la table ENSEIGNEMENT
CREATE TABLE ENSEIGNEMENT (
    ID_Ens VARCHAR(255) PRIMARY KEY,
    nom_Ens VARCHAR(255),
    Semestre INT,
    Coefficient INT,
    ID_UE VARCHAR(255),
    type VARCHAR(255),
    FOREIGN KEY (ID_UE) REFERENCES UE(ID_UE),
    FOREIGN KEY (type) REFERENCES TYPE_ENSEIGNEMENT(type)
);

-- Création de la table ENSEIGNANT
CREATE TABLE ENSEIGNANT (
    ID_prof VARCHAR(255) PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    adresse_mail VARCHAR(255),
    password VARCHAR(255)
);

-- Création de la table ELEVE
CREATE TABLE ELEVE (
    ID_eleve VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255),
    nom VARCHAR(255),
    prenom VARCHAR(255),
    adresse_mail VARCHAR(255),
    ID_classe INT,
    FOREIGN KEY (ID_classe) REFERENCES CLASSE(ID_classe)
);

-- Création de la table EPREUVE
CREATE TABLE EPREUVE (
    ID_epreuve INT, 
    Date DATE, 
    Nom_epreuve VARCHAR(255), 
    note INT, 
    Coefficient INT,
    Appreciation VARCHAR(255),
    ID_Ens VARCHAR(255), 
    ID_eleve VARCHAR(255),
    ID_classe INT,
    FOREIGN KEY (ID_Ens) REFERENCES ENSEIGNEMENT(ID_Ens),
    FOREIGN KEY (ID_eleve) REFERENCES ELEVE(ID_eleve), 
    FOREIGN KEY (ID_classe) REFERENCES CLASSE(ID_classe)
);

-- Création de la table ENSEIGNE
CREATE TABLE ENSEIGNE (
    ID_prof VARCHAR(255),
    ID_Ens VARCHAR(255),
    PRIMARY KEY (ID_prof, ID_Ens),
    FOREIGN KEY (ID_prof) REFERENCES ENSEIGNANT(ID_prof),
    FOREIGN KEY (ID_Ens) REFERENCES ENSEIGNEMENT(ID_Ens)
);

-- Création de la table CONTIENT
CREATE TABLE CONTIENT (
    ID_UE VARCHAR(255),
    ID_Ens VARCHAR(255),
    PRIMARY KEY (ID_UE, ID_Ens),
    FOREIGN KEY (ID_UE) REFERENCES UE(ID_UE),
    FOREIGN KEY (ID_Ens) REFERENCES ENSEIGNEMENT(ID_Ens)
);

-- Insertion d'un administrateur
INSERT INTO ADMINISTRATEUR (ID_admin, nom, prenom, adresse_mail, password)
VALUES ('12345', 'Martin', 'Paul', 'administrateur@exemple.com','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq');

-- Insertion de 20 élèves dans la classe 2
INSERT INTO ELEVE (ID_eleve, password, nom, prenom, adresse_mail, ID_classe)
VALUES
('123460', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Durand', 'Paul', 'paul.durand@exemple.com', 2),
('123461', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Petit', 'Emma', 'emma.petit@exemple.com', 2),
('123462', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Leroy', 'Louis', 'louis.leroy@exemple.com', 2),
('123463', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Moreau', 'Julie', 'julie.moreau@exemple.com', 2),
('123464', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Simon', 'Hugo', 'hugo.simon@exemple.com', 2),
('123465', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Laurent', 'Laura', 'laura.laurent@exemple.com', 2),
('123466', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Rousseau', 'Tom', 'tom.rousseau@exemple.com', 2),
('123467', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Blanc', 'Anaïs', 'anais.blanc@exemple.com', 2),
('123468', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Garnier', 'Nathan', 'nathan.garnier@exemple.com', 2),
('123469', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Chevalier', 'Sarah', 'sarah.chevalier@exemple.com', 2),
('123470', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Muller', 'Maxime', 'maxime.muller@exemple.com', 2),
('123471', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Mercier', 'Lea', 'lea.mercier@exemple.com', 2),
('123472', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Michel', 'David', 'david.michel@exemple.com', 2),
('123473', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Garcia', 'Chloe', 'chloe.garcia@exemple.com', 2),
('123474', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Thomas', 'Gabriel', 'gabriel.thomas@exemple.com', 2),
('123475', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Perrin', 'Lena', 'lena.perrin@exemple.com', 2),
('123476', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Robin', 'Adam', 'adam.robin@exemple.com', 2);

-- Insertion de 20 élèves dans la classe 3
INSERT INTO ELEVE (ID_eleve, password, nom, prenom, adresse_mail, ID_classe)
VALUES 
('123477', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Lemoine', 'Alice', 'alice.lemoine@exemple.com', 3),
('123478', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Faure', 'Julien', 'julien.faure@exemple.com', 3),
('123479', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Bertrand', 'Sabrina', 'sabrina.bertrand@exemple.com', 3),
('123480', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Marty', 'Victor', 'victor.marty@exemple.com', 3),
('123481', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Lefevre', 'Elodie', 'elodie.lefevre@exemple.com', 3),
('123482', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Pires', 'Nicolas', 'nicolas.pires@exemple.com', 3),
('123483', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Dufour', 'Clara', 'clara.dufour@exemple.com', 3),
('123484', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Fontaine', 'Marius', 'marius.fontaine@exemple.com', 3),
('123485', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Clement', 'Elise', 'elise.clement@exemple.com', 3),
('123486', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Joly', 'Mathieu', 'mathieu.joly@exemple.com', 3),
('123487', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Marchand', 'Lisa', 'lisa.marchand@exemple.com', 3),
('123488', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Barre', 'Romain', 'romain.barre@exemple.com', 3),
('123489', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Renard', 'Estelle', 'estelle.renard@exemple.com', 3),
('123490', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Charpentier', 'Quentin', 'quentin.charpentier@exemple.com', 3),
('123491', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Guillet', 'Ines', 'ines.guillet@exemple.com', 3),
('123492', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Bourgeois', 'Antoine', 'antoine.bourgeois@exemple.com', 3),
('123493', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Fleury', 'Caroline', 'caroline.fleury@exemple.com', 3),
('123494', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Michaud', 'Axel', 'axel.michaud@exemple.com', 3),
('123495', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Poulain', 'Fanny', 'fanny.poulain@exemple.com', 3),
('123496', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Dumont', 'Leo', 'leo.dumont@exemple.com', 3);

-- Insertion de 3 élèves dans la classe 4
INSERT INTO ELEVE (ID_eleve, password, nom, prenom, adresse_mail, ID_classe)
VALUES 
('123457', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Martin', 'Sophie', 'sophie.martin@exemple.com', 4),
('123458', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Bernard', 'Lucas', 'lucas.bernard@exemple.com', 4),
('123459', '$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq', 'Dubois', 'Marie', 'marie.dubois@exemple.com', 4);

-- Insertion d'un prof
INSERT INTO ENSEIGNANT(ID_prof, nom, prenom, password, adresse_mail) VALUES
('25','Toryan','Dissier','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','toryan@exemple.com'),
('24','Clement','Sigalas','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','prof@exemple.com'),
('23','Marianne','Durand','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','marianne.durand@exemple.com'),
('22','Jean','Dupont','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','jean.dupont@exemple.com'),
('21','Sylvie','Martin','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','sylvie.martin@exemple.com'),
('20','Patrick','Bernard','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','patrick.bernard@exemple.com'),
('19','Isabelle','Petit','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','isabelle.petit@exemple.com'),
('18','Nathalie','Robert','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','nathalie.robert@exemple.com'),
('17','Alain','Richard','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','alain.richard@exemple.com'),
('16','Christine','Dubois','$2b$12$xEpU9B4dSarUrQXjZCWwSOgdm8PBzpTZjT2ihDeSTfWVg7nPTiKaq','christine.dubois@exemple.com');

-- Insertion des types d'épreuve
INSERT INTO TYPE_ENSEIGNEMENT (type) VALUES ('SAE');
INSERT INTO TYPE_ENSEIGNEMENT (type) VALUES ('Ressource');
INSERT INTO TYPE_ENSEIGNEMENT (type) VALUES ('Autre');

-- Insertion des UE
INSERT INTO UE (ID_UE, Competence) VALUES
('1', 'Comprendre'),
('2', 'Concevoir'),
('3', 'Exprimer'),
('4', 'Developper'),
('5', 'Entreprendre');

-- Insertion des enseignements
INSERT INTO ENSEIGNEMENT (ID_Ens, nom_Ens, Semestre, Coefficient, ID_UE, type) VALUES
('666372c2e0618', 'Culture Artistique', 2, 2, '1', 'Autre'),
('666376efe324c', 'Culture Numérique', 1, 1, '1', 'Ressource');

-- Insertion de la liaison entre l'enseignant et l'enseignement
INSERT INTO ENSEIGNE (ID_prof, ID_Ens) VALUES ('25', '666372c2e0618');

INSERT INTO `epreuve` (`ID_epreuve`, `Date`, `Nom_epreuve`, `note`, `Coefficient`, `Appreciation`, `ID_Ens`, `ID_eleve`, `ID_classe`) VALUES
(1, '2024-06-14', 'Devoir sur table 1', 11, 3, 'Très bon travail !', '666372c2e0618', '123457', 4),
(1, '2024-06-14', 'Devoir sur table 1', 12, 3, 'Plutot satisfaisant !', '666372c2e0618', '123458', 4),
(1, '2024-06-14', 'Devoir sur table 1', 11, 3, 'Peut mieux faire !', '666372c2e0618', '123459', 4);