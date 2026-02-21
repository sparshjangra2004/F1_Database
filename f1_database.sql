CREATE DATABASE IF NOT EXISTS F1_championship;
USE F1_championship;
CREATE TABLE Teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) NOT NULL UNIQUE,
    country VARCHAR(50) NOT NULL,
    team_principal VARCHAR(100),
    founded_year YEAR,
    engine_supplier VARCHAR(100)
);

CREATE TABLE Drivers (
    driver_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    nationality VARCHAR(50),
    driver_number INT NOT NULL UNIQUE,
    team_id INT,
    status ENUM('Active','Reserve','Retired') DEFAULT 'Active',
    FOREIGN KEY (team_id) REFERENCES Teams(team_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE Seasons (
    season_id INT AUTO_INCREMENT PRIMARY KEY,
    year YEAR NOT NULL UNIQUE,
    total_races INT CHECK (total_races > 0)
);

CREATE TABLE Circuits (
    circuit_id INT AUTO_INCREMENT PRIMARY KEY,
    circuit_name VARCHAR(100) NOT NULL,
    country VARCHAR(50),
    length_km DECIMAL(4,2),
    turns INT,
    lap_record TIME
);

CREATE TABLE Races (
    race_id INT AUTO_INCREMENT PRIMARY KEY,
    race_name VARCHAR(100),
    race_date DATE,
    season_id INT,
    circuit_id INT,
    weather_condition ENUM('Dry','Wet','Mixed'),
    laps INT CHECK (laps > 0),
    FOREIGN KEY (season_id) REFERENCES Seasons(season_id)
        ON DELETE CASCADE,
    FOREIGN KEY (circuit_id) REFERENCES Circuits(circuit_id)
        ON DELETE RESTRICT
);

CREATE TABLE Results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    race_id INT,
    driver_id INT,
    grid_position INT CHECK (grid_position > 0),
    finish_position INT CHECK (finish_position > 0),
    points DECIMAL(4,1) CHECK (points >= 0),
    fastest_lap BOOLEAN DEFAULT FALSE,
    status ENUM('Finished','DNF','DSQ'),
    UNIQUE (race_id, driver_id),
    FOREIGN KEY (race_id) REFERENCES Races(race_id)
        ON DELETE CASCADE,
    FOREIGN KEY (driver_id) REFERENCES Drivers(driver_id)
        ON DELETE CASCADE
);

CREATE TABLE Sponsors (
    sponsor_id INT AUTO_INCREMENT PRIMARY KEY,
    sponsor_name VARCHAR(100) UNIQUE,
    industry VARCHAR(100),
    country VARCHAR(50)
);   

CREATE TABLE Team_Sponsors (
    team_sponsor_id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    sponsor_id INT NOT NULL,
    contract_start DATE NOT NULL,
    contract_end DATE NOT NULL,

    CONSTRAINT unique_team_sponsor UNIQUE (team_id, sponsor_id),

    FOREIGN KEY (team_id)
        REFERENCES Teams(team_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (sponsor_id)
        REFERENCES Sponsors(sponsor_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO Teams (team_name, country, team_principal, founded_year, engine_supplier)
VALUES
('Red Bull Racing','Austria','Christian Horner',2005,'Honda'),
('Mercedes AMG Petronas','Germany','Toto Wolff',2010,'Mercedes'),
('Ferrari','Italy','Frédéric Vasseur',1950,'Ferrari');

INSERT INTO Drivers (first_name,last_name,date_of_birth,nationality,driver_number,team_id)
VALUES
('Max','Verstappen','1997-09-30','Dutch',1,1),
('Sergio','Perez','1990-01-26','Mexican',11,1),
('Lewis','Hamilton','1985-01-07','British',44,2),
('Charles','Leclerc','1997-10-16','Monégasque',16,3);

INSERT INTO Circuits (circuit_name,country,length_km,turns)
VALUES
('Silverstone Circuit','UK',5.89,18),
('Monza Circuit','Italy',5.79,11);

INSERT INTO Seasons (year,total_races)
VALUES (2024,24);

INSERT INTO Races (race_name,race_date,season_id,circuit_id,weather_condition,laps)
VALUES
('British Grand Prix','2024-07-07',1,1,'Dry',52),
('Italian Grand Prix','2024-09-01',1,2,'Dry',53);

INSERT INTO Results (race_id,driver_id,grid_position,finish_position,points,fastest_lap,status)
VALUES
(1,1,1,1,25,TRUE,'Finished'),
(1,3,2,2,18,FALSE,'Finished'),
(1,4,3,3,15,FALSE,'Finished'),
(2,1,1,2,18,FALSE,'Finished'),
(2,4,2,1,25,TRUE,'Finished');

INSERT INTO Sponsors (sponsor_name,industry,country)
VALUES
('Oracle','Technology','USA'),
('Petronas','Energy','Malaysia'),
('Shell','Oil & Gas','Netherlands');

INSERT INTO Team_Sponsors (team_id,sponsor_id,contract_start,contract_end)
VALUES
(1,1,'2023-01-01','2026-12-31'),
(2,2,'2022-01-01','2025-12-31'),
(3,3,'2021-01-01','2024-12-31');

INSERT INTO Admins (username, password)
VALUES ('sparsh', '$2y$10$KgDj4AEStqeX9qzXkOX38eN85uaXQC3iC3d5PixyTtZhMuLuwrFZ2');

SELECT d.driver_id, d.first_name, d.last_name,
       SUM(r.points) AS total_points
FROM Drivers d
JOIN Results r ON d.driver_id = r.driver_id
GROUP BY d.driver_id
ORDER BY total_points DESC;

SELECT t.team_name,
       SUM(r.points) AS team_points
FROM Teams t
JOIN Drivers d ON t.team_id = d.team_id
JOIN Results r ON d.driver_id = r.driver_id
GROUP BY t.team_id
ORDER BY team_points DESC;

SELECT d.first_name, d.last_name,
       AVG(r.finish_position) AS avg_finish
FROM Drivers d
JOIN Results r ON d.driver_id = r.driver_id
GROUP BY d.driver_id;

SELECT s.sponsor_name,
       COUNT(ts.team_id) AS total_teams
FROM Sponsors s
JOIN Team_Sponsors ts ON s.sponsor_id = ts.sponsor_id
GROUP BY s.sponsor_id;

SELECT d.driver_id, COUNT(*) AS podiums
FROM Drivers d
JOIN Results r ON d.driver_id = r.driver_id
WHERE r.finish_position <= 3
GROUP BY d.driver_id
HAVING podiums > 5;

SELECT d.first_name, ra.race_name, c.circuit_name, r.finish_position
FROM Results r
JOIN Drivers d ON r.driver_id = d.driver_id
JOIN Races ra ON r.race_id = ra.race_id
JOIN Circuits c ON ra.circuit_id = c.circuit_id;