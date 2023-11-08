# Dating.AI

## Overview

Dating.AI is an advanced web-based dating application aimed at revolutionizing the way people connect and interact online. The application leverages state-of-the-art technologies to provide a seamless and intuitive user experience. It incorporates a range of features, from user authentication and profile management to real-time chat and a sophisticated matchmaking algorithm. Designed with both scalability and performance in mind, Dating.AI is poised to set new standards in the online dating industry.

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Features](#features)
  - [User Authentication](#user-authentication)
  - [Profile Management](#profile-management)
  - [Real-time Chat](#real-time-chat)
  - [Matchmaking Algorithm](#matchmaking-algorithm)
  - [Advanced Backend Configuration](#advanced-backend-configuration)
  - [Interactive Admin Dashboard](#interactive-admin-dashboard)
  - [Scalable File Structure](#scalable-file-structure)
  - [Modern Tech Stack](#modern-tech-stack)
  - [Pre-populated Test Data](#pre-populated-test-data)
  - [Health Monitoring](#health-monitoring)
  - [Subscription and Billing Management](#subscription-and-billing-management)
  - [User Activity Logging](#user-activity-logging)
  - [Demo Content for User Engagement](#demo-content-for-user-engagement)
  - [Security Features](#security-features)
  - [Token System](#token-system)
- [Technologies Used](#technologies-used)
- [File Structure](#file-structure)

## Installation

1. Clone the repository to your local machine.
2. Import the `database.sql` file into your MySQL database.
3. Update the `config.php` and `database.php` files with your database credentials.
4. Run the application on your local server.

## Features

## Features

### User Authentication

The application provides a secure and efficient user authentication system. Users can sign up with their email and password, which are then encrypted and stored securely. The login process includes validation checks and session management.

### Profile Management

Users have the ability to create and manage their profiles. This includes uploading profile pictures, setting preferences, and managing personal information. The profile management system is designed to be user-friendly yet secure.

### Real-time Chat

One of the standout features of Dating.AI is the real-time chat functionality. Users can engage in conversations with their matches, send text messages, images, and even videos. The chat is implemented using WebSockets for low latency.

### Matchmaking Algorithm

The application employs a sophisticated matchmaking algorithm that takes into account various factors like interests, preferences, and geographical location. The algorithm is designed to provide the most compatible matches to the users, enhancing the overall user experience.

### Advanced Backend Configuration

The backend includes a robust SQL database structure with tables for users, chat history, billing records, and system health, indicating a well-thought-out data management system.

### Interactive Admin Dashboard

The application features a comprehensive admin dashboard with functionalities to manage users, billing, messages, plans, and user profiles, providing administrators with full control over the platform.

### Scalable File Structure

The repository's file structure is designed for scalability, with clear separation of backend configuration, scripts, and styles, allowing for easy maintenance and expansion.

### Modern Tech Stack

The application is built using PHP 7.x, MySQL, Bootstrap 4.5.2, and jQuery 3.5.1, indicating a modern and robust technology stack.

### Pre-populated Test Data

The SQL file includes test data for various tables, which can be useful for demonstration purposes and initial testing phases.

### Health Monitoring

The system_health table in the database is designed to monitor and record various metrics, ensuring the application's performance is consistently tracked.

### Subscription and Billing Management

The database structure includes tables for managing subscription plans and billing records, indicating a monetization strategy and financial management capability.

### User Activity Logging

There is a user_activity table designed to log user actions, which can be crucial for analytics and understanding user behavior.

### Demo Content for User Engagement

The personalities table contains demo profiles with descriptions, likes, dislikes, and other attributes, which can be used to engage users from the start.

### Security Features

The users table includes a 'banned' field, suggesting the application has measures to enforce community guidelines and user conduct.

### Token System

The model_usage table indicates a token system, which could be an innovative feature for in-app currency or prioritizing user actions.

## Technologies Used

- PHP 7.x
- MySQL
- Bootstrap 4.5.2
- jQuery 3.5.1

## File Structure
s
- `AI-CHARACTERS/`: - Directory containing images of AI-generated characters.
  - `1/istockphoto-639805094-612x612.jpg`
  - `2/front-view-smiling-woman.jpg`
  - `3/ai-generated-7962957_1280.jpg`
- `BACKEND/`: - Directory for backend files.
- `ADMIN/`: - Contains administrative PHP scripts and styles.
- `SCRIPTS/admin_scripts.js`: - JavaScript for admin functionalities.
- `STYLES/admin_style.css`: - CSS styles for the admin panel.
  - `admin_billing.php`: - Admin billing management page.
  - `admin_cost_estimator.php`: - Admin cost estimation tool.
  - `admin_dashboard.php`: - Main dashboard for admin.
  - `admin_footer.php`: - Footer for admin pages.
  - `admin_functions.php`: - Functions specific to admin operations.
  - `admin_head.php`: - Head file for admin pages including scripts and styles.
  - `admin_header.php`: - Header for admin pages.
  - `admin_messages.php`: - Admin page for managing messages.
  - `admin_overview.php`: - Overview statistics for admin.
  - `admin_plans.php`: - Admin management for subscription plans.
  - `admin_user_profile.php`: - Admin view for user profiles.
  - `admin_users.php`: - Admin user management page.
  - `index.php`: - Entry point for the admin section.
- `CONFIG/`: - Configuration files for the application.
  - `config.php`: - General configuration settings.
  - `database.php`: - Database connection settings.
  - `database.sql`: - SQL schema for the application.
- `SCRIPTS/`: - JavaScript libraries and scripts.
  - `bootstrap-4.5.2.min.js`: - Minified Bootstrap JS library.
  - `jquery-3.5.1.min.js`: - Minified jQuery library.
  - `lobby.js`: - Script for lobby functionalities.
  - `popper-1.16.0.min.js`: - Popper JS library. 
- `STYLES/`: - CSS stylesheets.
  - `bootstrap-4.5.2.min.css`: - Minified Bootstrap CSS library.
  - `main.css`: - Main stylesheet for the application.
- `USER_DATA/`: - Directory for user-related data.
  - `3ccee7a6350a6b806ae38eb44d129413/1699217721.png`
  - `7b7bc2512ee1fedcd76bdc68926d4f7b/1699080091.jpg`
- `README.md`: - The markdown file containing information about the project.
- `chatroom.php`: - Chatroom functionality.
- `footer.php`: - Footer for the main pages.
- `functions.php`: - General functions used throughout the application.
- `head.php`: - Head file including scripts and styles for the main pages.
- `header.php`: - Header for the main pages.
- `index.php`: - Main entry point for the application.
- `lobby.php`: - Lobby page for user interactions.
- `login.php`: - Login functionality.
- `modal.php`: - Modal popups for the application.
- `profile.php`: - User profile management.
- `signout.php`: - Sign out functionality.
- `signup.php`: - Sign up functionality.