-- SQL File for DatingAI Database Structure

-- Create database if it doesn't exist
-- This statement ensures that a database named 'DatingAI' is created if it does not already exist.
-- The character set and collation are set to utf8mb4 and utf8mb4_unicode_ci respectively for supporting a wide range of characters.
CREATE DATABASE IF NOT EXISTS DatingAI CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
-- This command switches to the 'DatingAI' database for subsequent operations.
USE DatingAI;

-- Create users table if it doesn't exist
-- This table stores user information. Each user has a unique ID, username, email, and other personal details.
-- 'AUTO_INCREMENT' is used for the 'id' to automatically increment this field for new records.
-- 'PRIMARY KEY' is set on 'id' to uniquely identify each record.
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each user
    username VARCHAR(50) NOT NULL,  -- Username chosen by the user
    email VARCHAR(50) NOT NULL,  -- User's email address
    age INT,  -- User's age
    preferences JSON,  -- User's preferences, possibly in JSON format
    password VARCHAR(255) NOT NULL,  -- Hashed password for user authentication
    profile_picture VARCHAR(255),  -- URL or path to the user's profile picture
    addr1 VARCHAR(255),  -- Address line 1 for the user
    addr2 VARCHAR(255),  -- Address line 2 for the user
    zip VARCHAR(10),  -- ZIP/postal code for the user's address
    state VARCHAR(50),  -- State or region for the user's address
    country VARCHAR(50),  -- Country for the user's address
    first_name VARCHAR(50),  -- User's first name
    last_name VARCHAR(50),  -- User's last name
    subscription INT,  -- Subscription type or level for the user
    role INT,  -- Role identifier, useful for role-based access control
    banned BOOLEAN,  -- Flag to indicate if the user is banned
    signup_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of user signup
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp of the user's last login
    total_messages_sent INT,  -- Total number of messages sent by the user
    total_cost_of_queries FLOAT,  -- Total cost incurred by the user for queries
    tokens_sent INT DEFAULT 0,  -- Number of tokens sent by the user
    tokens_received INT DEFAULT 0  -- Number of tokens received by the user
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create characters table if it doesn't exist
-- This table stores detailed information about each character created in the app.
CREATE TABLE IF NOT EXISTS characters (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each character
    first_name VARCHAR(50),  -- First name of the character
    last_name VARCHAR(50),  -- Last name of the character
    profile_picture VARCHAR(255),  -- URL or path to the character's profile picture
    current_location VARCHAR(100),  -- Current location of the character
    sex VARCHAR(50),  -- Gender of the character
    age INT,  -- Age or age appearance of the character
    bio TEXT,  -- Short biography or description of the character
    interests JSON,  -- JSON field containing a list of interests and hobbies
    dislikes JSON,  -- JSON field containing a list of dislikes
    personality_traits JSON,  -- JSON field containing specific personality traits
    physical_characteristics JSON,  -- JSON field for physical characteristics like height, build, hair color, etc.
    voice_tone VARCHAR(255),  -- Description or reference to the character's voice style
    style_of_interaction VARCHAR(255),  -- General style of interaction (e.g., formal, casual)
    ai_model_type VARCHAR(255),  -- Type or version of the AI model used for the character
    customization_options JSON,  -- JSON field for additional customizable features
    status VARCHAR(50),  -- Status of the character (e.g., active, inactive)
    availability_schedule JSON,  -- JSON field for available hours or conditions
    creator_user_id INT UNSIGNED,  -- ID of the user who created or primarily interacts with the character
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of character creation
    last_modified_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp of the last update to the character
    language_preferences JSON,  -- JSON field for preferred languages for interaction
    cultural_references TEXT,  -- Field for specific cultural nuances or references
    emotional_intelligence_level INT,  -- Metric to measure the AI's ability to handle emotional contexts
    FOREIGN KEY (creator_user_id) REFERENCES users(id)  -- Linking character to a specific user
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create interaction_history table if it doesn't exist
-- This table stores the history of interactions between users and characters.
CREATE TABLE IF NOT EXISTS interaction_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each interaction record
    characters_id INT UNSIGNED,  -- ID of the character involved in the interaction
    user_id INT UNSIGNED,  -- ID of the user involved in the interaction
    interaction_details TEXT,  -- Detailed description or log of the interaction
    interaction_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of when the interaction occurred
    FOREIGN KEY (characters_id) REFERENCES characters(id),  -- Linking to the characters table
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Linking to the users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create learning_outcomes table if it doesn't exist
-- This table tracks the learning outcomes and relationship levels between users and characters.
CREATE TABLE IF NOT EXISTS learning_outcomes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each learning outcome record
    user_id INT UNSIGNED,  -- ID of the user associated with the learning outcome
    characters_id INT UNSIGNED,  -- ID of the character associated with the learning outcome
    learned_data JSON,  -- JSON field containing data learned about the user
    relationship_level INT,  -- Metric to gauge the user-character bond
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of the last update to the learning outcome
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Linking to the users table
    FOREIGN KEY (characters_id) REFERENCES characters(id)  -- Linking to the characters table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create chat history table if it doesn't exist
CREATE TABLE IF NOT EXISTS chat_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each chat entry
    user_id INT UNSIGNED,  -- ID of the user involved in the chat
    characters_id INT UNSIGNED,  -- ID of the character involved in the chat
    message TEXT NOT NULL,  -- The message sent by the user
    response TEXT NOT NULL,  -- The response from the character
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of the chat
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key to users table
    FOREIGN KEY (characters_id) REFERENCES characters(id)  -- Foreign key to personalities table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create UserDeletedConversation table if it doesn't exist
CREATE TABLE IF NOT EXISTS UserDeletedConversation (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each deleted conversation entry
    user_id INT UNSIGNED,  -- ID of the user involved in the deleted conversation
    characters_id INT UNSIGNED,  -- ID of the character involved in the deleted conversation
    message TEXT NOT NULL,  -- The message that was deleted
    response TEXT NOT NULL,  -- The response that was deleted
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of the deletion
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key to users table
    FOREIGN KEY (characters_id) REFERENCES characters(id)  -- Foreign key to personalities table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create model_usage table if it doesn't exist
CREATE TABLE IF NOT EXISTS model_usage (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each model usage entry
    user_id INT UNSIGNED NOT NULL,  -- ID of the user using the model
    model_name VARCHAR(255) NOT NULL,  -- Name of the AI model used
    usage_count INT DEFAULT 0,  -- Count of how many times the model has been used
    last_used TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Last time the model was used
    tokens_sent INT DEFAULT 0,  -- Tokens sent by the user for using the model
    tokens_received INT DEFAULT 0,  -- Tokens received by the user for using the model
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key to users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create billing_records table if it doesn't exist
CREATE TABLE IF NOT EXISTS billing_records (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each billing record
    user_id INT UNSIGNED NOT NULL,  -- ID of the user associated with the billing record
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date of the transaction
    amount DECIMAL(10,2) NOT NULL,  -- Amount of the transaction
    transaction_type VARCHAR(50) NOT NULL,  -- Type of transaction (e.g., subscription renewal, token purchase)
    status VARCHAR(50) NOT NULL,  -- Status of the transaction (e.g., completed, pending)
    item_description TEXT,  -- Description of the item purchased
    item_quantity INT DEFAULT 1,  -- Quantity of the item purchased
    item_price DECIMAL(10,2) NOT NULL,  -- Price per item
    item_total DECIMAL(10,2) AS (item_quantity * item_price),  -- Total cost of the item(s)
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key to users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create system_health table if it doesn't exist
CREATE TABLE IF NOT EXISTS system_health (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each system health record
    metric_name VARCHAR(255) NOT NULL,  -- Name of the metric being recorded
    metric_value VARCHAR(255) NOT NULL,  -- Value of the metric
    recorded_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Timestamp when the metric was recorded
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create user_activity table if it doesn't exist
CREATE TABLE IF NOT EXISTS user_activity (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each user activity record
    user_id INT UNSIGNED NOT NULL,  -- ID of the user whose activity is being recorded
    activity_type VARCHAR(255) NOT NULL,  -- Type of activity (e.g., login, message sent)
    activity_description TEXT,  -- Detailed description of the activity
    activity_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the activity occurred
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key to users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_tokens table if it doesn't exist
CREATE TABLE IF NOT EXISTS user_tokens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each user token record
    user_id INT UNSIGNED NOT NULL,  -- ID of the user owning the tokens
    token_count INT DEFAULT 0,  -- Number of tokens the user has
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Last time the token count was updated
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key to users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_reports table if it doesn't exist
CREATE TABLE IF NOT EXISTS user_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each user report
    reported_user_id INT UNSIGNED NOT NULL,  -- ID of the user being reported
    reporting_user_id INT UNSIGNED NOT NULL,  -- ID of the user making the report
    report_reason TEXT NOT NULL,  -- Reason for the report
    report_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the report was made
    FOREIGN KEY (reported_user_id) REFERENCES users(id),  -- Foreign key to users table (reported user)
    FOREIGN KEY (reporting_user_id) REFERENCES users(id)  -- Foreign key to users table (reporting user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user_preferences table if it doesn't exist
CREATE TABLE IF NOT EXISTS user_preferences (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each user preference record
    user_id INT UNSIGNED NOT NULL,  -- ID of the user whose preferences are being recorded
    preference_name VARCHAR(255) NOT NULL,  -- Name of the preference
    preference_value VARCHAR(255) NOT NULL,  -- Value of the preference
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key to users table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create subscription_plans table if it doesn't exist
CREATE TABLE IF NOT EXISTS subscription_plans (
    plan_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each subscription plan
    name VARCHAR(255) NOT NULL,  -- Name of the subscription plan
    price DECIMAL(10, 2) NOT NULL COMMENT 'Price of the plan',  -- Price of the subscription plan
    duration INT UNSIGNED NOT NULL COMMENT 'Duration in days',  -- Duration of the plan in days
    description TEXT,  -- Description of the subscription plan
    status ENUM('active', 'inactive', 'promotional') NOT NULL DEFAULT 'active',  -- Status of the plan (active, inactive, promotional)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the plan was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Timestamp when the plan was last updated
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create subscriptions table if it doesn't exist
CREATE TABLE IF NOT EXISTS subscriptions (
    subscription_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each user subscription
    user_id INT UNSIGNED NOT NULL,  -- ID of the user who has the subscription
    plan_id INT UNSIGNED NOT NULL,  -- ID of the subscription plan
    start_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  -- Start date of the subscription
    end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- End date of the subscription (no default value set)
    status ENUM('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active',  -- Status of the subscription (active, expired, cancelled)
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key to users table
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(plan_id)  -- Foreign key to subscription_plans table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Test data for users table
INSERT INTO users (username, email, age, preferences, password, profile_picture, addr1, addr2, zip, state, country, first_name, last_name, subscription, role, banned, signup_date, last_login, total_messages_sent, total_cost_of_queries, tokens_sent, tokens_received) VALUES
('JohnDoe', 'john.doe@example.com', 30, NULL, '$2y$10$GCE6n.56fMVoHLjtNcZbD.sbE1mcFuzZveICmLZVbYgsKdXBVuOTK', NULL, '123 Main St', 'Apt 4', '12345', 'NY', 'USA', 'John', 'Doe', 1, 0, FALSE, '2023-11-08 01:07:55', '2023-11-10 17:20:52', 10, 5, 100, 50),
('JaneDoe', 'jane.doe@example.com', 28, NULL, 'password123', NULL, '456 Elm St', 'Suite 789', '67890', 'CA', 'USA', 'Jane', 'Doe', 2, 1, FALSE, '2023-11-08 01:07:55', '2023-11-08 01:07:55', 20, 10, 200, 150),

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
INSERT INTO user_activity (user_id, activity_type, activity_description, activity_timestamp) VALUES
(1, 'Login', 'User logged in from IP: 192.168.1.1', CURRENT_TIMESTAMP),
(2, 'Profile Update', 'User updated profile picture', CURRENT_TIMESTAMP),
(3, 'Message', 'User sent a message to personality_id: 1', CURRENT_TIMESTAMP),
(4, 'Subscription', 'User upgraded to Tier Three Subscription', CURRENT_TIMESTAMP),
(5, 'Login', 'User logged in from IP: 172.16.254.1', CURRENT_TIMESTAMP);

-- Example test data for characters table
INSERT INTO characters (first_name, last_name, profile_picture, current_location, sex, age, bio, interests, dislikes, personality_traits, physical_characteristics, voice_tone, style_of_interaction, ai_model_type, customization_options, status, availability_schedule, creator_user_id, language_preferences, cultural_references, emotional_intelligence_level)
VALUES ('Alex', 'Smith', 'AI-CHARACTERS\\1\\istockphoto-639805094-612x612.jpg', 'New York', 'Male', 30, 'Friendly and knowledgeable AI companion', '["reading", "coding"]', '["loud noises"]', '{"kind": "true", "intelligent": "true"}', '{"height": "6ft", "hair_color": "brown"}', 'Calm and soothing', 'Casual', 'gpt-4-1106-preview', '{"hair_style": "short", "eye_color": "blue"}', 'active', '{"weekdays": "9am-5pm"}', 1, '["English", "Spanish"]', 'Enjoys American and Spanish culture', 5);

-- Example test data for interaction_history table
INSERT INTO interaction_history (characters_id, user_id, interaction_details)
VALUES (1, 1, 'Discussed latest technology trends');

-- Example test data for learning_outcomes table
INSERT INTO learning_outcomes (user_id, characters_id, learned_data, relationship_level)
VALUES (1, 1, JSON_OBJECT('favorite_topics', JSON_ARRAY('AI', 'Machine Learning')), 3);

-- Test data for chat_history table
INSERT INTO chat_history (user_id, characters_id, message, response, timestamp) VALUES
(1, 1, 'Hello John!', 'Hi there! How are you?', CURRENT_TIMESTAMP),
(2, 1, 'Hi Jane.', 'Hello! How can I assist you today?', CURRENT_TIMESTAMP),
(3, 1, 'Hey Eve, got any travel tips?', 'Sure! Have you ever visited the Grand Canyon?', CURRENT_TIMESTAMP),
(4, 1, 'Adam, I need help with my computer.', 'Of course! What seems to be the issue?', CURRENT_TIMESTAMP),
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

-- Test data for personalities table OLD TABLE FOR CHARACTERS TABLE. 
-- INSERT INTO personalities (first_name, last_name, profile_picture, description, notes, likes, dislikes, sex, location, engine) VALUES
-- ('John', 'Smith', 'AI-CHARACTERS\\1\\istockphoto-639805094-612x612.jpg', 'Friendly and outgoing.', 'Loves to chat.', 'Coffee, Books', 'Loud noises', 'male', 'New York', 'gpt-3.5-turbo-16k'),
-- ('Jane', 'Doe', 'AI-CHARACTERS\\2\\front-view-smiling-woman.jpg', 'Introverted but thoughtful.', 'Enjoys solitude.', 'Reading, Music', 'Crowds', 'female', 'San Francisco', 'gpt-3.5-turbo-16k'),
-- ('Eve', 'Adams', 'AI-CHARACTERS\\3\\eve_adams.jpg', 'Adventurous and creative.', 'Seeks new experiences.', 'Travel, Photography', 'Routine', 'female', 'Austin', 'gpt-3.5-turbo-16k'),
-- ('Adam', 'Johnson', 'AI-CHARACTERS\\4\\adam_johnson.jpg', 'Analytical and curious.', 'Problem solver.', 'Puzzles, Technology', 'Inefficiency', 'male', 'Seattle', 'gpt-3.5-turbo-16k');
