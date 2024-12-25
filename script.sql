CREATE DATABASE FitBook;
USE FitBook;

CREATE TABLE membres (
    MembreID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(50),
    Prenom VARCHAR(50),
    Telephone VARCHAR(20),
    Email VARCHAR(100)
);

CREATE TABLE activites (
    ActiviteID INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(50),
    Description TEXT
);

CREATE TABLE reservations (
    ReservationID INT PRIMARY KEY AUTO_INCREMENT,
    MembreID INT,
    ActiviteID INT,
    ReservationDate DATETIME,
    FOREIGN KEY (MembreID) REFERENCES Membres(MembreID),
    FOREIGN KEY (ActiviteID) REFERENCES Activites(ActiviteID)
);

-- Example of inserting --

INSERT INTO membres (Nom, Prenom, Telephone, Email) VALUES
('CHAMKHI', 'Mohammed', '212-636-253939', 'theshamkhi1@gmail.com'),
('MARSLI', 'Younes', '123-456-789', 'marsli.younes@gmail.com');

INSERT INTO activites (Nom, Description) VALUES
('Yoga', 'A relaxing activity focusing on stretching and mindfulness.'),
('Spinning', 'A high-intensity cycling class for cardiovascular fitness.'),
('Pilates', 'A low-impact exercise for flexibility, strength, and posture.'),
('CrossFit', 'A high-intensity workout combining strength and cardio exercises.');

INSERT INTO reservations (MembreID, ActiviteID, ReservationDate) VALUES 
(1, 1, '2024-12-15 09:00:00'),
(2, 2, '2024-12-16 10:30:00');


-- Example of modifying --

UPDATE activites 
SET Description = 'An intensive cycling class designed to improve stamina.'
WHERE ActiviteID = 2;

UPDATE reservations 
SET ReservationDate = '2024-12-17 11:00:00'
WHERE ReservationID = 2;

-- Example of deleting --

DELETE FROM membres 
WHERE MembreID = 2;

DELETE FROM activites 
WHERE ActiviteID = 4;

DELETE FROM reservations 
WHERE ReservationID = 1;
