CREATE DATABASE Gym;
USE Gym;

CREATE TABLE Users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(100),
    Password VARCHAR(100),
    Role ENUM('Admin', 'Member')
);

CREATE TABLE Members (
    MemberID INT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    FOREIGN KEY (MemberID) REFERENCES Users (UserID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Activities (
    ActivityID INT AUTO_INCREMENT PRIMARY KEY,
    PhotoURL VARCHAR(255),
    Name VARCHAR(255) NOT NULL,
    Description TEXT
);

CREATE TABLE Reservations (
    ResID INT AUTO_INCREMENT PRIMARY KEY,
    MemberID INT NOT NULL,
    ActivityID INT NOT NULL,
    status ENUM('Confirmed', 'Cancelled', 'Pending') DEFAULT 'Pending',
    ResDate DATETIME NOT NULL,
    FOREIGN KEY (MemberID) REFERENCES Members(MemberID),
    FOREIGN KEY (ActivityID) REFERENCES Activities(ActivityID)
);