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

-- Create characters table if it doesn't exist

-- characters table stores the static attributes of each AI character
CREATE TABLE IF NOT EXISTS characters (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),  -- Character's first name
    last_name VARCHAR(50),   -- Character's last name
    profile_picture VARCHAR(255),  -- URL to character's profile picture
    current_location VARCHAR(100), -- Character's current location
    sex VARCHAR(50),  -- Character's gender
    age INT,  -- Character's age
    bio TEXT,  -- Short biography or description of the character
    interests JSON,  -- List of character's interests and hobbies
    dislikes JSON,  -- List of character's dislikes
    personality_traits JSON,  -- Character's personality traits
    physical_characteristics JSON,  -- Character's physical characteristics
    voice_tone VARCHAR(255),  -- Description of character's voice tone
    style_of_interaction VARCHAR(255),  -- Character's general interaction style
    ai_model_type VARCHAR(255),  -- Type/version of AI model used
    customization_options JSON,  -- Additional customizable features
    status VARCHAR(50),  -- Character's current status (active, inactive, etc.)
    availability_schedule JSON,  -- Character's availability hours/conditions
    creator_user_id INT UNSIGNED,  -- ID of user who created/interacts with character
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of character creation
    last_modified_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp of last update
    language_preferences JSON,  -- Preferred languages for interaction
    cultural_references TEXT,  -- Specific cultural nuances or references
    emotional_intelligence_level INT,  -- AI's ability to handle emotional contexts
    FOREIGN KEY (creator_user_id) REFERENCES users(id)  -- Link to users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- interaction_history table records each interaction between users and characters
CREATE TABLE IF NOT EXISTS interaction_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    character_id INT UNSIGNED,  -- ID of the character involved in the interaction
    user_id INT UNSIGNED,  -- ID of the user involved in the interaction
    interaction_details TEXT,  -- Details of the interaction
    interaction_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of the interaction
    FOREIGN KEY (character_id) REFERENCES characters(id),
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Link to users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- learning_outcomes table tracks what AI has learned about each user
CREATE TABLE IF NOT EXISTS learning_outcomes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,  -- ID of the user
    character_id INT UNSIGNED,  -- ID of the character
    learned_data JSON,  -- Data learned about the user
    relationship_level INT,  -- Metric for user-character bond
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of last update
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (character_id) REFERENCES characters(id)  -- Link to characters table
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

-- Table for Subscription Plans
CREATE TABLE IF NOT EXISTS subscription_plans (
    plan_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL COMMENT 'Price of the plan',
    duration INT UNSIGNED NOT NULL COMMENT 'Duration in days',
    description TEXT,
    status ENUM('active', 'inactive', 'promotional') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for User Subscriptions
CREATE TABLE IF NOT EXISTS subscriptions (
    subscription_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    plan_id INT UNSIGNED NOT NULL,
    start_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- No DEFAULT clause
    status ENUM('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(plan_id)
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

-- Example test data for characters table
INSERT INTO characters (first_name, last_name, profile_picture, current_location, sex, age, bio, interests, dislikes, personality_traits, physical_characteristics, voice_tone, style_of_interaction, ai_model_type, customization_options, status, availability_schedule, creator_user_id, language_preferences, cultural_references, emotional_intelligence_level)
VALUES ('Alex', 'Smith', 'AI-CHARACTERS\\1\\istockphoto-639805094-612x612.jpg', 'New York', 'Male', 30, 'Friendly and knowledgeable AI companion', JSON_ARRAY('reading', 'coding'), JSON_ARRAY('loud noises'), JSON_OBJECT('kind', 'true', 'intelligent', 'true'), JSON_OBJECT('height', '6ft', 'hair_color', 'brown'), 'Calm and soothing', 'Casual', 'gpt-4-1106-preview', JSON_OBJECT('hair_style', 'short', 'eye_color', 'blue'), 'active', JSON_OBJECT('weekdays', '9am-5pm'), 1, JSON_ARRAY('English', 'Spanish'), 'Enjoys American and Spanish culture', 5);

-- Example test data for interaction_history table
INSERT INTO interaction_history (character_id, user_id, interaction_details)
VALUES (1, 1, 'Discussed latest technology trends');

-- Example test data for learning_outcomes table
INSERT INTO learning_outcomes (user_id, character_id, learned_data, relationship_level)
VALUES (1, 1, JSON_OBJECT('favorite_topics', JSON_ARRAY('AI', 'Machine Learning')), 3);

-- Test data for personalities table OLD TABLE FOR CHARACTERS TABLE. 
-- INSERT INTO personalities (first_name, last_name, profile_picture, description, notes, likes, dislikes, sex, location, engine) VALUES
-- ('John', 'Smith', 'AI-CHARACTERS\\1\\istockphoto-639805094-612x612.jpg', 'Friendly and outgoing.', 'Loves to chat.', 'Coffee, Books', 'Loud noises', 'male', 'New York', 'gpt-3.5-turbo-16k'),
-- ('Jane', 'Doe', 'AI-CHARACTERS\\2\\front-view-smiling-woman.jpg', 'Introverted but thoughtful.', 'Enjoys solitude.', 'Reading, Music', 'Crowds', 'female', 'San Francisco', 'gpt-3.5-turbo-16k'),
-- ('Eve', 'Adams', 'AI-CHARACTERS\\3\\eve_adams.jpg', 'Adventurous and creative.', 'Seeks new experiences.', 'Travel, Photography', 'Routine', 'female', 'Austin', 'gpt-3.5-turbo-16k'),
-- ('Adam', 'Johnson', 'AI-CHARACTERS\\4\\adam_johnson.jpg', 'Analytical and curious.', 'Problem solver.', 'Puzzles, Technology', 'Inefficiency', 'male', 'Seattle', 'gpt-3.5-turbo-16k');

-- Test data for chat_history table
INSERT INTO chat_history (user_id, personality_id, message, response, timestamp) VALUES
(1, 1, 'Hello John!', 'Hi there! How are you?', CURRENT_TIMESTAMP),
(2, 2, 'Hi Jane.', 'Hello! How can I assist you today?', CURRENT_TIMESTAMP),
(3, 3, 'Hey Eve, got any travel tips?', 'Sure! Have you ever visited the Grand Canyon?', CURRENT_TIMESTAMP),
(4, 4, 'Adam, I need help with my computer.', 'Of course! What seems to be the issue?', CURRENT_TIMESTAMP),
(5, 1, 'Good morning, John.', 'Good morning! What can I do for you today?', CURRENT_TIMESTAMP);

INSERT INTO subscription_plans (name, price, duration, description, status) VALUES
('Basic Plan', 9.99, 30, 'Basic subscription plan with limited features.', 'active'),
('Premium Plan', 19.99, 30, 'Premium subscription plan with full features.', 'active'),
('Annual Plan', 99.99, 365, 'One year subscription with a discount.', 'active'),
('Promotional Plan', 5.99, 30, 'Limited time offer with reduced price.', 'promotional');

INSERT INTO subscriptions (user_id, plan_id, start_date, end_date, status) VALUES
(1, 1, '2023-11-01 00:00:00', '2023-12-01 00:00:00', 'active'),
(2, 2, '2023-11-01 00:00:00', '2023-12-01 00:00:00', 'active'),
(3, 3, '2023-01-01 00:00:00', '2024-01-01 00:00:00', 'expired'),
(4, 4, '2023-11-01 00:00:00', '2023-12-01 00:00:00', 'cancelled');

-- Role and Subscription Classification:
-- Roles: 0 = New User, 1 = Moderator, 2 = Admin, 3 = Superadmin, 100 = Owner.
-- Subscriptions: 1 = Tier One Subscription, 2 = Tier Two Subscription, 3 = Tier Three Subscription (VIP).