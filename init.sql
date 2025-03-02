-- Tabela ras
CREATE TABLE IF NOT EXISTS races (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    life_factor FLOAT,
    mana_factor FLOAT,
    agility_factor FLOAT
);

ALTER TABLE races CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Tabela użytkowników
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255),
    google_id VARCHAR(50) DEFAULT NULL,
    race_id INT NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    points INT DEFAULT 0,
    level INT DEFAULT 1,
    last_login DATETIME,
    FOREIGN KEY (race_id) REFERENCES races(id) ON DELETE CASCADE
);

-- Dodanie trzech ras
INSERT INTO races (name, description, life_factor, mana_factor, agility_factor) VALUES
('człowiek', 'Uniwersalna rasa o zrównoważonych statystykach.', 1.0, 1.0, 1.0),
('ork', 'Silni wojownicy, odporni ale mniej zręczni.', 1.5, 0.7, 0.8),
('elf', 'Zręczni łucznicy i magowie o wysokiej inteligencji.', 0.8, 1.5, 1.2);

-- Pobranie ID dla ras
SET @human_id = (SELECT id FROM races WHERE name = 'człowiek');
SET @orc_id = (SELECT id FROM races WHERE name = 'ork');
SET @elf_id = (SELECT id FROM races WHERE name = 'elf');

-- Wstawienie przykładowych użytkowników
INSERT INTO users (username, email, password, google_id, race_id, gender, points, level, last_login) VALUES
('Gracz1', 'gracz1', 'haslo', NULL, @orc_id, 'male', 50, 1, NOW()),
('Gracz2', 'gracz2', 'haslo', NULL, @human_id, 'female', 150, 2, NOW()),
('Gracz3', 'gracz3', 'haslo', NULL, @elf_id, 'male', 90, 1, NOW());
