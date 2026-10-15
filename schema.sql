-- HoopTracker schema + sample data (MySQL / MariaDB)
-- Run this in phpMyAdmin (Import tab) or: mysql -u root < schema.sql
-- WARNING: it drops and recreates the tables, so re-running wipes anything you added.
-- Sample rows are placeholders for testing. Replace the stats and images with real data as you like.
-- All sample users have the password: password123

CREATE DATABASE IF NOT EXISTS hooptracker CHARACTER SET utf8mb4;
USE hooptracker;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS Media, Fav_Player, Fav_Team, MVP_Pick, Game, Player, Team, Conference, User;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE User (
    UID         CHAR(9)      PRIMARY KEY,
    F_Name      VARCHAR(50),
    L_Name      VARCHAR(50),
    Username    VARCHAR(50)  NOT NULL UNIQUE,
    Password    VARCHAR(255) NOT NULL,
    Email       VARCHAR(100),
    PhoneNumber VARCHAR(20),
    ProfileURL  VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE Conference (
    Location VARCHAR(50) PRIMARY KEY,
    Division VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Team (
    Team_ID     INT PRIMARY KEY AUTO_INCREMENT,
    Team_Name   VARCHAR(50) NOT NULL,
    Coach_FName VARCHAR(50),
    Coach_LName VARCHAR(50),
    Location    VARCHAR(50),
    Win         INT DEFAULT 0,
    Lose        INT DEFAULT 0,
    LogoURL     VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE Player (
    Player_ID INT PRIMARY KEY AUTO_INCREMENT,
    F_Name    VARCHAR(50) NOT NULL,
    L_Name    VARCHAR(50) NOT NULL,
    Age       INT,
    Year      INT,
    Position  VARCHAR(20),
    Points    DECIMAL(4,1),
    Rebounds  DECIMAL(4,1),
    Assists   DECIMAL(4,1),
    PlayerURL VARCHAR(255),
    Team_ID   INT,
    FOREIGN KEY (Team_ID) REFERENCES Team(Team_ID)
) ENGINE=InnoDB;

CREATE TABLE Game (
    Game_ID    INT PRIMARY KEY AUTO_INCREMENT,
    Home_Score INT,
    Away_Score INT,
    Game_Time  TIME,
    Game_Date  DATE,
    Location   VARCHAR(100),
    Ticket     VARCHAR(255),
    Home_Team  INT,
    Away_Team  INT,
    FOREIGN KEY (Home_Team) REFERENCES Team(Team_ID),
    FOREIGN KEY (Away_Team) REFERENCES Team(Team_ID)
) ENGINE=InnoDB;

CREATE TABLE MVP_Pick (
    MVP_ID      INT PRIMARY KEY AUTO_INCREMENT,
    Season      INT NOT NULL,
    Rank_Number INT NOT NULL,
    Player_ID   INT NOT NULL,
    FOREIGN KEY (Player_ID) REFERENCES Player(Player_ID)
) ENGINE=InnoDB;

CREATE TABLE Fav_Team (
    UID     CHAR(9) NOT NULL,
    Team_ID INT     NOT NULL,
    PRIMARY KEY (UID, Team_ID),
    FOREIGN KEY (UID) REFERENCES User(UID),
    FOREIGN KEY (Team_ID) REFERENCES Team(Team_ID)
) ENGINE=InnoDB;

CREATE TABLE Fav_Player (
    UID       CHAR(9) NOT NULL,
    Player_ID INT     NOT NULL,
    PRIMARY KEY (UID, Player_ID),
    FOREIGN KEY (UID) REFERENCES User(UID),
    FOREIGN KEY (Player_ID) REFERENCES Player(Player_ID)
) ENGINE=InnoDB;

CREATE TABLE Media (
    Media_ID     CHAR(9) PRIMARY KEY,
    UID          CHAR(9) NOT NULL,
    Team_ID      INT     NOT NULL,
    Media_Type   VARCHAR(20),
    Description  VARCHAR(1000),
    MediaURL     VARCHAR(255) NOT NULL,
    Like_Counter INT DEFAULT 0,
    FOREIGN KEY (UID) REFERENCES User(UID),
    FOREIGN KEY (Team_ID) REFERENCES Team(Team_ID)
) ENGINE=InnoDB;

-- ---------- Sample data ----------

INSERT INTO User (UID, F_Name, L_Name, Username, Password, Email, PhoneNumber, ProfileURL) VALUES
('000000001','Alex','Rivera','hoopfan23','$2y$12$lajzPlTKYcdlELw7BJKHtuMGdyNirKsitOIeDHZOh8FZESXmHytnm','alex@example.com','555-0101','https://placehold.co/80x80.png?text=A'),
('000000002','Jordan','Lee','courtvision','$2y$12$lajzPlTKYcdlELw7BJKHtuMGdyNirKsitOIeDHZOh8FZESXmHytnm','jordan@example.com','555-0102','https://placehold.co/80x80.png?text=J'),
('000000003','Sam','Ortiz','fastbreak','$2y$12$lajzPlTKYcdlELw7BJKHtuMGdyNirKsitOIeDHZOh8FZESXmHytnm','sam@example.com','555-0103','https://placehold.co/80x80.png?text=S'),
('000000004','Casey','Nguyen','dunkzone','$2y$12$lajzPlTKYcdlELw7BJKHtuMGdyNirKsitOIeDHZOh8FZESXmHytnm','casey@example.com','555-0104','https://placehold.co/80x80.png?text=C'),
('000000005','Dwight','Kool','DwighKool','$2y$12$lajzPlTKYcdlELw7BJKHtuMGdyNirKsitOIeDHZOh8FZESXmHytnm','dwight@example.com','555-0105','https://placehold.co/80x80.png?text=D');

INSERT INTO Conference (Location, Division) VALUES
('Los Angeles','Pacific'),('Boston','Atlantic'),('San Francisco','Pacific'),('Miami','Southeast'),
('Denver','Northwest'),('Milwaukee','Central'),('Phoenix','Pacific'),('New York','Atlantic'),
('Oklahoma City','Northwest'),('Dallas','Southwest');

INSERT INTO Team (Team_ID, Team_Name, Coach_FName, Coach_LName, Location, Win, Lose, LogoURL) VALUES
(1,'Lakers','Chris','Hall','Los Angeles',50,32,'https://placehold.co/60x60.svg?text=LAL'),
(2,'Celtics','Dana','Brooks','Boston',64,18,'https://placehold.co/60x60.svg?text=BOS'),
(3,'Warriors','Pat','Morgan','San Francisco',46,36,'https://placehold.co/60x60.svg?text=GSW'),
(4,'Heat','Lee','Foster','Miami',44,38,'https://placehold.co/60x60.svg?text=MIA'),
(5,'Nuggets','Robin','Cole','Denver',57,25,'https://placehold.co/60x60.svg?text=DEN'),
(6,'Bucks','Taylor','Reed','Milwaukee',49,33,'https://placehold.co/60x60.svg?text=MIL'),
(7,'Suns','Jamie','Price','Phoenix',36,46,'https://placehold.co/60x60.svg?text=PHX'),
(8,'Knicks','Morgan','Bell','New York',51,31,'https://placehold.co/60x60.svg?text=NYK'),
(9,'Thunder','Quinn','Ward','Oklahoma City',68,14,'https://placehold.co/60x60.svg?text=OKC'),
(10,'Mavericks','Drew','Shaw','Dallas',39,43,'https://placehold.co/60x60.svg?text=DAL');

INSERT INTO Player (Player_ID, F_Name, L_Name, Age, Year, Position, Points, Rebounds, Assists, PlayerURL, Team_ID) VALUES
(1,'LeBron','James',40,22,'F',24.4,7.8,8.2,'https://placehold.co/60x60.png?text=LJ',1),
(2,'Jayson','Tatum',27,8,'F',26.8,8.7,6.0,'https://placehold.co/60x60.png?text=JT',2),
(3,'Stephen','Curry',37,16,'G',24.5,4.4,6.0,'https://placehold.co/60x60.png?text=SC',3),
(4,'Jimmy','Butler',36,14,'F',17.0,5.5,5.0,'https://placehold.co/60x60.png?text=JB',4),
(5,'Nikola','Jokic',30,10,'C',29.6,12.7,10.2,'https://placehold.co/60x60.png?text=NJ',5),
(6,'Giannis','Antetokounmpo',31,12,'F',30.4,11.9,6.5,'https://placehold.co/60x60.png?text=GA',6),
(7,'Kevin','Durant',37,18,'F',26.6,6.0,4.2,'https://placehold.co/60x60.png?text=KD',7),
(8,'Jalen','Brunson',29,8,'G',26.0,3.2,7.3,'https://placehold.co/60x60.png?text=JB',8),
(9,'Shai','Gilgeous-Alexander',27,7,'G',32.7,5.0,6.4,'https://placehold.co/60x60.png?text=SGA',9),
(10,'Luka','Doncic',27,7,'G',28.2,8.2,7.7,'https://placehold.co/60x60.png?text=LD',10);

INSERT INTO Game (Game_ID, Home_Score, Away_Score, Game_Time, Game_Date, Location, Ticket, Home_Team, Away_Team) VALUES
(1,112,104,'19:30:00','2025-01-10','Crypto.com Arena','https://example.com/tickets/1',1,2),
(2,118,121,'20:00:00','2025-01-12','Chase Center','https://example.com/tickets/2',3,5),
(3,109,101,'19:00:00','2025-01-15','Kaseya Center','https://example.com/tickets/3',4,8),
(4,125,117,'18:30:00','2025-01-18','Paycom Center','https://example.com/tickets/4',9,10),
(5,99,105,'19:30:00','2025-01-20','Fiserv Forum','https://example.com/tickets/5',6,7);

INSERT INTO MVP_Pick (Season, Rank_Number, Player_ID) VALUES
(2019,1,6),(2020,1,6),(2021,1,5),(2022,1,5),(2024,1,5),(2025,1,9),(2015,1,3),(2016,1,3);

INSERT INTO Fav_Team (UID, Team_ID) VALUES
('000000001',1),('000000001',2),('000000002',5),('000000003',9),('000000004',1),('000000005',5);

INSERT INTO Fav_Player (UID, Player_ID) VALUES
('000000001',1),('000000002',5),('000000003',9),('000000004',3),('000000005',5);

INSERT INTO Media (Media_ID, UID, Team_ID, Media_Type, Description, MediaURL, Like_Counter) VALUES
('000000101','000000005',5,'image','Jokic triple-double night','https://placehold.co/300x200.png?text=Nuggets',12),
('000000102','000000005',1,'image','Lakers fast break highlights','https://placehold.co/300x200.png?text=Lakers',7),
('000000103','000000001',2,'image','Banner night in Boston','https://placehold.co/300x200.png?text=Celtics',9),
('000000104','000000003',9,'image','OKC is rolling','https://placehold.co/300x200.png?text=Thunder',15),
('000000105','000000002',3,'image','Curry from deep','https://placehold.co/300x200.png?text=Warriors',4);