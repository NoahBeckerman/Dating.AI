<?php

/**
 * Functions for Dating.AI application.
 *
 * This file contains utility functions that are used across the Dating.AI application.
 * These functions provide functionalities like database operations, user authentication,
 * and interaction with the OpenAI API.
 *
 * @package Dating.AI
 */

require_once "../CONFIG/database.php";

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Execute a database query and return the result.
 *
 * This function is a wrapper around the Database class's executeQuery method.
 * It executes a given SQL query with optional parameters and returns the result.
 *
 * @param string $query  The SQL query to execute.
 * @param array  $params Optional parameters for the query.
 *
 * @return array|bool The result set as an associative array, or false on failure.
 *
 * Note: This function is designed to handle SELECT queries. It is not intended for queries that modify the database.
 */
function executeQuery($query, $params = [])
{
    global $database;
    return $database->executeQuery($query, $params);
}

/**
 * Execute a non-query database statement.
 *
 * @param string $query The SQL query.
 * @param array $params The query parameters.
 * @return bool True if the statement executed successfully, false otherwise.
 * Note: This function is designed to handle SQL statements that do not return a result set, such as INSERT, UPDATE, and DELETE queries.
 */
function executeNonQuery($query, $params = [])
{
    global $database;
    return $database->executeNonQuery($query, $params);
}

/**
 * Check if the user is logged in.
 *
 * @return bool True if the user is logged in, false otherwise.
 *
 * Note: The function checks for the presence of 'user_id' in the session, which should be set upon successful user authentication.
 */
function isLoggedIn()
{
    // Check if the 'user_id' session variable is set and not empty
    return isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"]);
}


function currentUserIsAdmin() {
    // Check if a user is logged in first
    if (!isLoggedIn()) {
       
        return false;
    }

    // Get the user's ID from the session
    $userId = $_SESSION['user_id'];

    // Query to select the role of the current user
    $query = "SELECT role FROM users WHERE id = ?";
    $result = executeQuery($query, [$userId]);

    // If the query returns a result and the 'role' is greater than 0, the user has admin privileges
    if (!empty($result) && $result[0]['role'] > 0){
        
    return true;
    }
    else 
    {  
        return false;
    };
}

/**
 * Redirect to a specific page.
 *
 * @param string $page The page to redirect to.
 */
function redirect($page)
{
    header("Location: $page");
    exit();
}

function getSystemHealth() {
    global $database;

    // Fetch all system health records
    $query = "SELECT * FROM system_health ORDER BY recorded_timestamp DESC";
    $result = $database->executeQuery($query);

    // Check if the query was successful
    if ($result) {
        // Return all records
        return $result;
    } else {
        // Return an empty array or handle the error as appropriate
        return [];
    }
}

?>