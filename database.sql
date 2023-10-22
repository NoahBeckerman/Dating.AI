CREATE DATABASE IF NOT EXISTS your_database_name;
USE your_database_name;
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS personalities (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255),
    description TEXT,
    notes TEXT,
    likes TEXT,
    dislikes TEXT,
    sex ENUM('male', 'female'),
    location VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS chat_history (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    personality_id INT(11) UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (personality_id) REFERENCES personalities(id)
);
INSERT INTO users (email, username, password) VALUES
    ('test1@example.com', 'test1', 'password1'),
    ('test2@example.com', 'test2', 'password2'),
    ('test3@example.com', 'test3', 'password3');