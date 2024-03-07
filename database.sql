DROP DATABASE IF EXISTS BitTweets;

CREATE DATABASE BitTweets;

USE BitTweets;

CREATE TABLE gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruikersnaam VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    wachtwoord VARCHAR(255) NOT NULL,
    registratiedatum DATETIME NOT NULL,
    verjaardag DATE,
    profielfoto VARCHAR(255),
    rang VARCHAR(255) DEFAULT 'Student',
    UNIQUE KEY idx_unique_gebruikersnaam (gebruikersnaam)
);

CREATE TABLE berichten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruikersnaam VARCHAR(255) NOT NULL,
    tekst TEXT NOT NULL,
    datum_tijd TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_gepind BOOLEAN NOT NULL DEFAULT 0
);

INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, registratiedatum, rang, profielfoto, verjaardag)
VALUES
('adminvoorbeeld', 'admin@voorbeeld.nl', 'Adminaccount!', NOW(), 'Admin', './images/default.jpg', NOW()),
('coachvoorbeeld', 'coach@voorbeeld.nl', 'Coachaccount!', NOW(), 'Coach', './images/default.jpg', NOW()),
('studentvoorbeeld', 'student@voorbeeld.nl', 'Studentaccount!', NOW(), 'Student', './images/default.jpg', NOW());

CREATE UNIQUE INDEX idx_unique_email ON gebruikers (email);