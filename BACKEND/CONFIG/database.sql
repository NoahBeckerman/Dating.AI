-- SQL File for DatingAI Database Structure

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS DatingAI CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE DatingAI;

-- Create users table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    age INT,
    preferences VARCHAR(255), 
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255),
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
    total_cost_of_queries FLOAT,
    tokens_sent INT DEFAULT 0,
    tokens_received INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create personalities table if it doesn't exist
CREATE TABLE IF NOT EXISTS personalities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    profile_picture VARCHAR(255),
    description TEXT,
    notes TEXT,
    likes TEXT,
    dislikes TEXT,
    sex VARCHAR(10),
    location VARCHAR(50),
    engine TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create chat history table if it doesn't exist
CREATE TABLE IF NOT EXISTS chat_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    personality_id INT UNSIGNED,
    message TEXT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (personality_id) REFERENCES personalities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create UserDeletedConversation table if it doesn't exist
CREATE TABLE IF NOT EXISTS UserDeletedConversation (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    personality_id INT UNSIGNED,
    message TEXT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (personality_id) REFERENCES personalities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create model_usage table if it doesn't exist
CREATE TABLE IF NOT EXISTS model_usage (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    model_name VARCHAR(255) NOT NULL,
    usage_count INT DEFAULT 0,
    last_used TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tokens_sent INT DEFAULT 0,
    tokens_received INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create billing_records table if it doesn't exist
CREATE TABLE IF NOT EXISTS billing_records (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10,2) NOT NULL,
    transaction_type VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL,
    item_description TEXT,
    item_quantity INT DEFAULT 1,
    item_price DECIMAL(10,2) NOT NULL,
    item_total DECIMAL(10,2) AS (item_quantity * item_price),
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create system_health table if it doesn't exist
CREATE TABLE IF NOT EXISTS system_health (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    metric_name VARCHAR(255) NOT NULL,
    metric_value VARCHAR(255) NOT NULL,
    recorded_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_activity table if it doesn't exist
CREATE TABLE IF NOT EXISTS user_activity (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    action_type VARCHAR(50) NOT NULL,
    action_description TEXT,
    action_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Test data for users table
INSERT INTO users (username, email, age, preferences, password, profile_picture, addr1, addr2, zip, state, country, first_name, last_name, subscription, role, banned, signup_date, last_login, total_messages_sent, total_cost_of_queries, tokens_sent, tokens_received) VALUES
('JohnDoe', 'john.doe@example.com', NULL, NULL, 'password123', NULL, '123 Main St', 'Apt 4', '12345', 'NY', 'USA', 'John', 'Doe', 1, 0, FALSE, '2023-10-22 07:29:33', '2023-10-22 07:29:33', 10, 5.00, 100, 50),
('JaneDoe', 'jane.doe@example.com', NULL, NULL, 'password123', NULL, '456 Elm St', 'Suite 789', '67890', 'CA', 'USA', 'Jane', 'Doe', 2, 1, FALSE, '2023-10-22 07:29:33', '2023-10-22 07:29:33', 20, 10.00, 200, 150),

-- Test data for model_usage table
INSERT INTO model_usage (user_id, model_name, usage_count, last_used, tokens_sent, tokens_received) VALUES
(1, 'gpt-3.5-turbo-16k', 10, CURRENT_TIMESTAMP, 100, 50),
(2, 'gpt-3.5-turbo-16k', 20, CURRENT_TIMESTAMP, 200, 150),
(3, 'gpt-3.5-turbo-16k', 5, CURRENT_TIMESTAMP, 50, 25),
(4, 'gpt-3.5-turbo-16k', 30, CURRENT_TIMESTAMP, 300, 200),
(5, 'gpt-3.5-turbo-16k', 50, CURRENT_TIMESTAMP, 500, 250);

-- Test data for billing_records table
INSERT INTO billing_records (user_id, transaction_date, amount, transaction_type, status, item_description, item_quantity, item_price) VALUES
(1, CURRENT_TIMESTAMP, 100.00, 'Subscription Renewal', 'Completed', 'Tier One Subscription', 1, 100.00),
(2, CURRENT_TIMESTAMP, 200.00, 'Subscription Renewal', 'Completed', 'Tier Two Subscription', 1, 200.00),
(3, CURRENT_TIMESTAMP, 50.00, 'Token Purchase', 'Completed', '500 Tokens', 1, 50.00),
(4, CURRENT_TIMESTAMP, 150.00, 'Token Purchase', 'Completed', '1500 Tokens', 1, 150.00),
(5, CURRENT_TIMESTAMP, 250.00, 'Token Purchase', 'Completed', '2500 Tokens', 1, 250.00);

-- Test data for system_health table
INSERT INTO system_health (metric_name, metric_value) VALUES
('Server Uptime', '99.99%'),
('Active Users', '1024'),
('Average Response Time', '200ms'),
('Peak Load', '75%');

-- Test data for user_activity table
INSERT INTO user_activity (user_id, action_type, action_description) VALUES
(1, 'Login', 'User logged in from IP: 192.168.1.1'),
(2, 'Profile Update', 'User updated profile picture'),
(3, 'Message', 'User sent a message to personality_id: 1'),
(4, 'Subscription', 'User upgraded to Tier Three Subscription'),
(5, 'Login', 'User logged in from IP: 172.16.254.1');

-- Test data for personalities table
INSERT INTO personalities (first_name, last_name, profile_picture, description, notes, likes, dislikes, sex, location, engine) VALUES
('John', 'Smith', 'AI-CHARACTERS\\1\\istockphoto-639805094-612x612.jpg', 'Friendly and outgoing.', 'Loves to chat.', 'Coffee, Books', 'Loud noises', 'male', 'New York', 'gpt-3.5-turbo-16k'),
('Jane', 'Doe', 'AI-CHARACTERS\\2\\front-view-smiling-woman.jpg', 'Introverted but thoughtful.', 'Enjoys solitude.', 'Reading, Music', 'Crowds', 'female', 'San Francisco', 'gpt-3.5-turbo-16k'),
('Eve', 'Adams', 'AI-CHARACTERS\\3\\eve_adams.jpg', 'Adventurous and creative.', 'Seeks new experiences.', 'Travel, Photography', 'Routine', 'female', 'Austin', 'gpt-3.5-turbo-16k'),
('Adam', 'Johnson', 'AI-CHARACTERS\\4\\adam_johnson.jpg', 'Analytical and curious.', 'Problem solver.', 'Puzzles, Technology', 'Inefficiency', 'male', 'Seattle', 'gpt-3.5-turbo-16k');

-- Test data for chat_history table
INSERT INTO chat_history (user_id, personality_id, message, response, timestamp) VALUES
(1, 1, 'Hello John!', 'Hi there! How are you?', CURRENT_TIMESTAMP),
(2, 2, 'Hi Jane.', 'Hello! How can I assist you today?', CURRENT_TIMESTAMP),
(3, 3, 'Hey Eve, got any travel tips?', 'Sure! Have you ever visited the Grand Canyon?', CURRENT_TIMESTAMP),
(4, 4, 'Adam, I need help with my computer.', 'Of course! What seems to be the issue?', CURRENT_TIMESTAMP),
(5, 1, 'Good morning, John.', 'Good morning! What can I do for you today?', CURRENT_TIMESTAMP);

-- Role and Subscription Classification:
-- Roles: 0 = New User, 1 = Moderator, 2 = Admin, 3 = Superadmin, 100 = Owner.
-- Subscriptions: 1 = Tier One Subscription, 2 = Tier Two Subscription, 3 = Tier Three Subscription (VIP).