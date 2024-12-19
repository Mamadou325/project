CREATE DATABASE login;
USE login;

CREATE TABLE Users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    isAdmin BOOLEAN DEFAULT 0,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Admins (
    adminID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    club_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (userID) REFERENCES Users(userID)
);


