-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS DatingAI CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


-- Use the database
USE DatingAI;

-- Create users table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    age int,
    preferences VARCHAR(255), 
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
    signup_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    total_messages_sent INT,
    total_cost_of_queries FLOAT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create personalities table if it doesn't exist
CREATE TABLE IF NOT EXISTS personalities (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create chat history table if it doesn't exist
CREATE TABLE IF NOT EXISTS chat_history (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    personality_id INT(11) UNSIGNED,
    message TEXT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (personality_id) REFERENCES personalities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create UserDeletedConversation table if it doesn't exist
CREATE TABLE IF NOT EXISTS UserDeletedConversation (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    personality_id INT(11) UNSIGNED,
    message TEXT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (personality_id) REFERENCES personalities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Test data for users table
INSERT INTO users (email, username, password, addr1, addr2, zip, state, country, first_name, last_name, subscription, role, banned, signup_date, last_login, total_messages_sent, total_cost_of_queries) VALUES
('john.doe@example.com', 'JohnDoe', 'password123', '123 Main St', 'Apt 4', '12345', 'NY', 'USA', 'John', 'Doe', 1, 0, FALSE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 10, 5.00),
('jane.doe@example.com', 'JaneDoe', 'password123', '456 Elm St', 'Suite 789', '67890', 'CA', 'USA', 'Jane', 'Doe', 2, 1, FALSE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 20, 10.00);

-- Test data for personalities table
INSERT INTO personalities (first_name, last_name, profile_picture, description, notes, likes, dislikes, sex, location, pre_prompt) VALUES
('John', 'Smith', 'john_smith.jpg', 'Friendly and outgoing.', 'Loves to chat.', 'Coffee, Books', 'Loud noises', 'male', 'New York', 'You are talking to John, a friendly and outgoing person.'),
('Jane', 'Doe', 'jane_doe.jpg', 'Introverted but thoughtful.', 'Enjoys solitude.', 'Reading, Music', 'Crowds', 'female', 'San Francisco', 'You are talking to Jane, an introverted but thoughtful person.');

-- Test data for chat_history table
INSERT INTO chat_history (user_id, personality_id, message, response, timestamp) VALUES
(1, 1, 'Hello John!', 'Hi there! How are you?', CURRENT_TIMESTAMP),
(2, 2, 'Hi Jane.', 'Hello! How can I assist you today?', CURRENT_TIMESTAMP);

-- Role and Subscription Classification:
-- Roles: 0 = New User, 1 = Moderator, 2 = Admin, 3 = Superadmin, 100 = Owner.
-- Subscriptions: 1 = Tier One Subscription, 2 = Tier Two Subscription, 3 = Tier Three Subscription (VIP).

