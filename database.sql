-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS DatingAI;

-- Use the database
USE DatingAI;

-- Create users table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    addr1 VARCHAR(255),
    addr2 VARCHAR(255),
    zip VARCHAR(10),
    state VARCHAR(50),
    country VARCHAR(50),
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    subscription INT,
    role INT,
    banned BOOLEAN,
    signup_date TIMESTAMP,
    last_login TIMESTAMP,
    total_messages_sent INT,
    total_cost_of_queries FLOAT
);

-- Create personalities table if it doesn't exist
CREATE TABLE IF NOT EXISTS personalities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    profile_picture VARCHAR(255),
    description TEXT,
    notes TEXT,
    likes TEXT,
    dislikes TEXT,
    sex VARCHAR(10),
    location VARCHAR(50),
    pre_prompt TEXT
);

-- Create chat history table if it doesn't exist
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
