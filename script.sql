CREATE TABLE Users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(100),
    Password VARCHAR(100),
    Role ENUM('Admin', 'Member')
);

CREATE TABLE Members (
    MemberID INT PRIMARY KEY,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    FOREIGN KEY (MemberID) REFERENCES Users (UserID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Activities (
    ActivityID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Description TEXT
);

CREATE TABLE Reservations (
    ResID INT AUTO_INCREMENT PRIMARY KEY,
    MemberID INT NOT NULL,
    ActivityID INT NOT NULL,
    status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'pending',
    ResDate DATETIME NOT NULL,
    FOREIGN KEY (MemberID) REFERENCES Members(MemberID),
    FOREIGN KEY (ActivityID) REFERENCES Activities(ActivityID)
);


-- Example of inserting --


INSERT INTO Membres (FirstName, LastName, Phone, Email) VALUES
('Mohammed', 'CHAMKHI', '212-636-253939', 'theshamkhi1@gmail.com'),
('Khadija', 'SAHNOUN', '123-456-789', 'khadija.sahnoun@gmail.com');

INSERT INTO Activities (Name, Description) VALUES
('Yoga', 'A relaxing activity focusing on stretching and mindfulness.'),
('Spinning', 'A high-intensity cycling class for cardiovascular fitness.'),
('Pilates', 'A low-impact exercise for flexibility, strength, and posture.'),
('CrossFit', 'A high-intensity workout combining strength and cardio exercises.');

INSERT INTO Reservations (MemberID, ActivityID, ResDate) VALUES 
(1, 1, '2024-12-15 09:00:00'),
(2, 2, '2024-12-16 10:30:00');