CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    score INT DEFAULT 0,
    wood INT DEFAULT 0,
    stone INT DEFAULT 0,
    iron INT DEFAULT 0,
    gold INT DEFAULT 0,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE building_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    building_name VARCHAR(50) NOT NULL,
    wood_cost INT NOT NULL,
    stone_cost INT NOT NULL,
    iron_cost INT NOT NULL,
    build_time INT NOT NULL  -- czas budowy w sekundach (lub minutach)
);

CREATE TABLE user_buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    building_type_id INT NOT NULL,
    level INT DEFAULT 1,
    build_started TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    build_complete TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (building_type_id) REFERENCES building_types(id) ON DELETE CASCADE
);

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_name VARCHAR(50) NOT NULL,
    description TEXT,
    wood_reward INT DEFAULT 0,
    stone_reward INT DEFAULT 0,
    iron_reward INT DEFAULT 0,
    gold_reward INT DEFAULT 0,
    difficulty INT DEFAULT 1
);

CREATE TABLE user_game_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    score INT DEFAULT 0,
    wood_gained INT DEFAULT 0,
    stone_gained INT DEFAULT 0,
    iron_gained INT DEFAULT 0,
    gold_gained INT DEFAULT 0,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);
