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
- [Technologies Used](#technologies-used)
- [File Structure](#file-structure)

## Installation

1. Clone the repository to your local machine.
2. Import the `database.sql` file into your MySQL database.
3. Update the `config.php` and `database.php` files with your database credentials.
4. Run the application on your local server.

## Features

### User Authentication

The application provides a secure and efficient user authentication system. Users can sign up with their email and password, which are then encrypted and stored securely. The login process includes validation checks and session management.

### Profile Management

Users have the ability to create and manage their profiles. This includes uploading profile pictures, setting preferences, and managing personal information. The profile management system is designed to be user-friendly yet secure.

### Real-time Chat

One of the standout features of Dating.AI is the real-time chat functionality. Users can engage in conversations with their matches, send text messages, images, and even videos. The chat is implemented using WebSockets for low latency.

### Matchmaking Algorithm

The application employs a sophisticated matchmaking algorithm that takes into account various factors like interests, preferences, and geographical location. The algorithm is designed to provide the most compatible matches to the users, enhancing the overall user experience.

## Technologies Used

- PHP 7.x
- MySQL
- Bootstrap 4.5.2
- jQuery 3.5.1

## File Structure

- `BACKEND/CONFIG/`: Contains configuration files for the application.
  - `config.php`: General configuration settings.
  - `database.php`: Database connection settings.
  - `database.sql`: SQL schema for the application.
- `SCRIPTS/`: Contains JavaScript libraries.
- `STYLES/`: Contains CSS files for styling.
- `*.php`: Various PHP files for different functionalities like login, signup, chatroom, etc.

