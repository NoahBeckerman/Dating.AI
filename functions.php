<?php
require_once 'config.php';
require_once 'database.php';



if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Execute a database query.
 *
 * @param string $query The SQL query.
 * @param array $params The query parameters.
 * @return array|bool The result of the query as an associative array, or false on failure.
 */
function executeQuery($query, $params = []) {
    global $database;
    return $database->executeQuery($query, $params);
}

/**
 * Execute a non-query database statement.
 *
 * @param string $query The SQL query.
 * @param array $params The query parameters.
 * @return bool True if the statement executed successfully, false otherwise.
 */
function executeNonQuery($query, $params = []) {
    global $database;
    return $database->executeNonQuery($query, $params);
}

/**
 * Check if the user is logged in.
 *
 * @return bool True if the user is logged in, false otherwise.
 */
function isLoggedIn() {
    // Check if the 'user_id' session variable is set and not empty
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redirect to a specific page.
 *
 * @param string $page The page to redirect to.
 */
function redirect($page) {
    header("Location: $page");
    exit();
}

/**
 * Handle user logout.
 */
function logout() {
    session_start();
    // Unset all session variables
    $_SESSION = array();
    // Destroy the session
    session_destroy();
    // Redirect to the login page
    redirect('login.php');
}

/**
 * Create the database, tables, and test data if they do not exist.
 */
function importDatabase() {
    global $database;
    // Check if the database exists
    $query = "SHOW DATABASES LIKE '" . DB_NAME . "'";
    $result = executeQuery($query);
    if (count($result) == 0) {
        // Import the database from database.sql
        $sql = file_get_contents('database.sql');
        $database->getConnection()->multi_query($sql);
    }
}

/**
 * Get the chat history for a user.
 *
 * @param int $userId The user ID.
 * @return array The chat history.
 */
function getChatHistory($userId) {
    $query = "SELECT chat_history.*, personalities.first_name, personalities.last_name
              FROM chat_history
              INNER JOIN personalities ON chat_history.personality_id = personalities.id
              WHERE chat_history.user_id = ?
              ORDER BY chat_history.timestamp DESC";
    return executeQuery($query, [$userId]);
}

/**
 * Get the previous chats for a user.
 *
 * @param int $userId The user ID.
 * @return array The previous chats.
 */
function getPreviousChats($userId) {
    $query = "SELECT DISTINCT chat_history.personality_id, personalities.first_name, personalities.last_name
              FROM chat_history
              INNER JOIN personalities ON chat_history.personality_id = personalities.id
              WHERE chat_history.user_id = ?";
    return executeQuery($query, [$userId]);
}

/**
 * Get the chat messages for a user and personality.
 *
 * @param int $userId The user ID.
 * @param int $personalityId The personality ID.
 * @return array The chat messages.
 */
function getChatMessages($userId, $personalityId) {
    $query = "SELECT message, response FROM chat_history
              WHERE user_id = ? AND personality_id = ?
              ORDER BY timestamp ASC";
    return executeQuery($query, [$userId, $personalityId]);
}

/**
 * Get the list of personalities.
 *
 * @return array The list of personalities.
 */
function getPersonalities() {
    $query = "SELECT * FROM personalities";
    return executeQuery($query);
}

/**
 * Get a user by username or email.
 *
 * @param string $usernameOrEmail The username or email.
 * @return array|null The user data or null if not found.
 */
function getUserByUsernameOrEmail($usernameOrEmail) {
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $result = executeQuery($query, [$usernameOrEmail, $usernameOrEmail]);
    return $result[0] ?? null;
}

/**
 * Store a chat record in the database.
 *
 * @param int $userId The user ID.
 * @param int $personalityId The personality ID.
 * @param string $message The user message.
 * @param string $response The AI response.
 */
function storeChatRecord($userId, $personalityId, $message, $response) {
    $query = "INSERT INTO chat_history (user_id, personality_id, message, response)
              VALUES (?, ?, ?, ?)";
    executeNonQuery($query, [$userId, $personalityId, $message, $response]);
}

/**
 * Send a message to the AI and get the response.
 *
 * @param string $message The user message.
 * @param int $personalityId The personality ID.
 * @return string The AI response.
 */
function sendMessage($message, $personalityId) {
    $personality = getPersonalityById($personalityId);
    $prompt = $personality['description'] . "\nUser: " . $message;
    $response = openaiApiCall($prompt);
    storeChatRecord($userId, $personalityId, $message, $response);
    return $response;
}

/**
 * Get a personality by ID.
 *
 * @param int $personalityId The personality ID.
 * @return array|null The personality data or null if not found.
 */
function getPersonalityById($personalityId) {
    $query = "SELECT * FROM personalities WHERE id = ?";
    $result = executeQuery($query, [$personalityId]);
    return $result[0] ?? null;
}


function userLogin($usernameOrEmail, $password) {
    $user = getUserByUsernameOrEmail($usernameOrEmail);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        addError('InvalidCredentials', 'Invalid username or password.');

    }
}

// Generic function for error messages

$errors = [];

function addError($errorTitle, $errorMessage) {
    global $errors;
    $errors[] = [
        'title' => $errorTitle,
        'message' => $errorMessage
    ];
}




/**
 * Make an API call to OpenAI to generate a response.
 *
 * @param string $prompt The user message prompt.
 * @return string The AI response.
 */
function openaiApiCall($prompt) {
    $api_key = OPENAI_API_KEY;
    $engine = ENGINE_NAME;
    $max_tokens = MAX_TOKENS;  // Retrieve max tokens from config.php
    $temperature = TEMPERATURE;  // Retrieve temperature from config.php
    $url = "https://api.openai.com/v1/engines/$engine/completions";

    $data = json_encode([
        'prompt' => $prompt,
        'max_tokens' => $max_tokens,
        'temperature' => $temperature  // Add temperature parameter
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($response, true);
    return $response_data['choices'][0]['text'];
}
