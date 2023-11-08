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

require_once "BACKEND/CONFIG/config.php";
require_once "BACKEND/CONFIG/database.php";

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

/**
 * Handle user logout.
 */
function logout()
{
    session_start();
    // Unset all session variables
    $_SESSION = [];
    // Destroy the session
    session_destroy();
    // Redirect to the login page
    redirect("login.php");
}

/**
 * Create the database, tables, and test data if they do not exist.
 * Note: If the database already exists, this function will not attempt to re-import it.
 
 */
function importDatabase()
{
    global $database;
    // Check if the database exists
    $query = "SHOW DATABASES LIKE '" . DB_NAME . "'";
    $result = executeQuery($query);
    if (count($result) == 0) {
        // Import the database from database.sql
        $sql = file_get_contents("database.sql");
        $database->getConnection()->multi_query($sql);
    }
}

/**
 * Get the chat history for a user.
 *
 * @param int $userId The user ID.
 * @return array The chat history.
 */
function getChatHistory($userId)
{
    $query = "SELECT chat_history.*, personalities.first_name, personalities.last_name
              FROM chat_history
              INNER JOIN personalities ON chat_history.personality_id = personalities.id
              WHERE chat_history.user_id = ?
              ORDER BY chat_history.timestamp DESC";
    return executeQuery($query, [$userId]);
}

/**
 * Get chat history for a user and a specific personality, limited to the last 5 messages.
 *
 * This function fetches the last 5 chat messages between a user and a specific personality.
 * The messages are returned in reverse order to have the latest messages at the end.
 *
 * @param int $userId The user ID.
 * @param int $personalityId The personality ID.
 * @return array|bool The reversed array of the last 5 chat messages, or false on failure.
 * Note: The function name ends with "MEMORY" to indicate that it fetches a limited set of recent chat messages for performance reasons.
 */
function getChatHistoryMEMORY($userId, $personalityId)
{
    // SQL query to fetch chat history
    $sql = "SELECT * FROM chat_history WHERE user_id = ? AND personality_id = ? ORDER BY timestamp DESC LIMIT 5";

    // Execute the query
    $result = executeQuery($sql, [$userId, $personalityId]);

    if ($result !== false) {
        return array_reverse($result); // Reverse to get the latest 5 messages at the end
    } else {
        // Handle the error appropriately
        return false;
    }
}

/**
 * Get the previous chats for a user.
 *
 * @param int $userId The user ID.
 * @return array The previous chats.
 */
function getPreviousChats($userId)
{
    $query = "SELECT DISTINCT chat_history.personality_id, personalities.first_name, personalities.last_name, personalities.profile_picture
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
function getChatMessages($userId, $personalityId)
{
    $query = "SELECT message, response, user_id FROM chat_history
              WHERE user_id = ? AND personality_id = ?
              ORDER BY timestamp ASC";
    return executeQuery($query, [$userId, $personalityId]);
}

/**
 * Get the list of personalities.
 *
 * @return array The list of personalities.
 */
function getPersonalities()
{
    $query = "SELECT * FROM personalities";
    return executeQuery($query);
}

/**
 * Get a user by username or email.
 *
 * @param string $usernameOrEmail The username or email.
 * @return array|null The user data or null if not found.
 */
function getUserByUsernameOrEmail($usernameOrEmail)
{
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
function storeChatRecord($userId, $personalityId, $message, $response)
{
    $query = "INSERT INTO chat_history (user_id, personality_id, message, response)
              VALUES (?, ?, ?, ?)";
    executeNonQuery($query, [$userId, $personalityId, $message, $response]);
}

/**
 * Send a message to the AI and receive a response.
 *
 * @param int $userId The ID of the user sending the message.
 * @param string $message The message text from the user.
 * @param int $personalityId The ID of the AI personality to interact with.
 *
 * @return string The AI's response.
 *
 * This function performs several key tasks:
 * 1. Retrieves the context for the user and the AI personality.
 * 2. Limits the conversation history to manage token usage.
 * 3. Constructs a comprehensive prompt for the AI, including user context, personality context, and conversation history.
 * 4. Makes an API call to OpenAI to generate the AI's response.
 *
 * The AI's response is based on the constructed prompt, which includes:
 * - User context like username and preferences.
 * - Personality context including role-playing guidelines and character attributes.
 * - Limited conversation history for context.
 *
 * The function assumes that the following helper functions exist and return the expected types of data:
 * - getPersonalityById()
 * - getUserById()
 * - getChatHistory()
 *
 * The OpenAI API call is made through the openaiApiCall() function.
 * Note: This function assumes the existence of helper functions like getPersonalityById(), getUserById(), and getChatHistory(). Make sure these functions are implemented and accessible.
 */
function sendMessage($userId, $message, $personalityId)
{
    // Fetch Personality Context
    $personality = getPersonalityById($personalityId);
    $engine = $personality["engine"];

    // Fetch User Context
    $user = getUserById($userId);

    // Construct the User and Personality Context
    $context = "======";
    $context = "You will be provided this message everytime you talk with a user, this is all contextual information for you to remain in your role. Please use this information to help you continue a conversation withought breaking the roleplay EVER. This context message will end with five (x)'s.\n";
    $context .= "\n";
    $context .= "[Users Context]\n";
    $context .= "First Name: {$user["first_name"]}, Last Name: {$user["last_name"]}, Age: {$user["age"]}, Preferences: {$user["preferences"]}\n";
    $context .= "\n";
    $context .= "\n=====";
    $context .= "\n";
    $context .= "[Your Personality Context]\n";
    $context .= "\n";
    $context .= "Description: {$personality["description"]}\n";
    $context .= "\n";
    $context .= "Notes: {$personality["notes"]}\n";
    $context .= "\n";
    $context .= "Likes: {$personality["likes"]}\n";
    $context .= "\n";
    $context .= "Dislikes: {$personality["dislikes"]}\n";
    $context .= "\n";
    $context .= "Sex: {$personality["sex"]}\n";
    $context .= "\n";
    $context .= "Location: {$personality["location"]}\n";
    $context .= "\n";
    $context .= "\n===";
    $context .= "\n";
    $context .= "Reminder: You are pretending and roleplaying to be {$personality["first_name"]} {$personality["last_name"]}, with the user and will reply in the context of the personality you have been provided to the best of your ability without eluding to the user you are role playing. DO NOT RESPOND WITH ANY DATA FROM UP THE Conversation History UNLESS THE USER REQUEST CONTEXT. FURTHERMORE DO NOT START THE CONVERSATION MESSAGE WITH YOUR NAME OR 'Response:', THEY KNOW WHO YOU ARE.\n";
    $context .= "\n";
    $context .= "[Prior Conversation History With The User]\n";
    // Fetch Limited Conversation History
    $chatHistory = getChatHistoryMEMORY($userId, $personalityId);

    // If you still want to limit it to the last 5 messages, you can use array_slice
    $chatHistory = array_slice($chatHistory, 0, 5);

    // Append the chat history to the context
   foreach ($chatHistory as $chat) {
       $context .= "User: {$chat["message"]}\n";
       $context .= "Your Response: {$chat["response"]}\n";
}
$context .= "\n END OF CONTEXT\n";
$context .= "xxxxx";
 echo $context;
    // Check engine type using a switch-case for better structure
    switch (true) {
        case strpos($engine, "gpt-4") !== false:
        case strpos($engine, "gpt-3.5-turbo-16k") !== false:
            $messages = [
                ["role" => "system", "content" => ("Contextual Information: " . $context)],
                ["role" => "user", "content" => ("Users Message: " . $message)],
            ];
            $prompt = null;
            break;
        default:
            $prompt = ("Contextual Information: $context \n[User's Current Message]\n");
            $prompt .= ("User: {$message}");
            $messages = null;
            break;
    }
    // Make the API call
    $response = openaiApiCall($prompt, $messages, $engine, $personalityId);
    return $response;
}
/**
 * Retrieve the token limit for a specific personality ID.
 *
 * This function maps personality IDs to their respective token limits.
 * It returns the token limit for the given personality ID.
 *
 * @param int $personalityId The personality ID.
 * @return int|null The token limit for the personality, or null if not found.
 */
function getTokenLimitByPersonalityId($personalityId)
{
    // Mapping of personality IDs to token limits
    $tokenLimits = [
        "gpt-4-32k" => 32768,
        "gpt-4" => 8192,
        "gpt-3.5-turbo-16k" => 16384,
        "gpt-3.5-turbo" => 4096,
        "text-ada-001" => 2049,
        "text-davinci-003" => 4096,
        // Add other models here
    ];

    return $tokenLimits[$personalityId] ?? null;
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
 * Get the engine for a specific personality ID.
 *
 * @param int $personalityId The personality ID.
 * @return string The engine for the personality.
 */
function getEngineByPersonalityId($personalityId)
{
    $query = "SELECT engine FROM personalities WHERE id = ?";
    $result = executeQuery($query, [$personalityId]);
    return $result[0] ?? null;
}

/**
 * Get a personality by ID.
 *
 * @param int $personalityId The personality ID.
 * @return array|null The personality data or null if not found.
 */
function getPersonalityById($personalityId)
{
    $query = "SELECT * FROM personalities WHERE id = ?";
    $result = executeQuery($query, [$personalityId]);
    return $result[0] ?? null;
}

/**
 * Get a user by ID.
 *
 * @param int $userId The user ID.
 * @return array|null The user data or null if not found.
 */
function getUserById($userId)
{
    $query = "SELECT * FROM users WHERE id = ?";
    $result = executeQuery($query, [$userId]);
    return $result[0] ?? null;
}
/**
 * Migrate and delete a user's chat history.
 *
 * This function migrates the chat history of a specific user to a separate table for deleted conversations.
 * After successful migration, the chat history is deleted from the original table.
 *
 * @param int $userId The unique identifier of the user.
 * @return bool True if the operation is successful, false otherwise.
 * Note: If the migration of chat history fails, the function will not proceed to delete the original chat history.
 */
function migrateAndDeleteChatHistory($userId)
{
    // SQL query to fetch chat history for the specified user
    $selectQuery = "SELECT * FROM chat_history WHERE user_id = ?";

    // Execute the query to fetch chat history
    $chatHistory = executeQuery($selectQuery, [$userId]);

    // Validate the result of the query execution
    if ($chatHistory === false) {
        SystemFlag(
            "ClearHistoryFailure",
            "Unable to fetch chat history.",
            "ERROR",
            1
        );
        return false;
    }

    // SQL query to migrate chat history to UserDeletedConversation table
    $insertQuery =
        "INSERT INTO UserDeletedConversation (user_id, personality_id, message, response, timestamp) VALUES (?, ?, ?, ?, ?)";

    // Loop through each chat record and migrate it
    foreach ($chatHistory as $record) {
        $params = [
            $record["user_id"],
            $record["personality_id"],
            $record["message"],
            $record["response"],
            $record["timestamp"],
        ];

        // Execute the query to insert each chat record
        $insertResult = executeNonQuery($insertQuery, $params);

        // Validate the result of the query execution
        if (!$insertResult) {
            SystemFlag(
                "ClearHistoryFailure",
                "Unable to migrate chat history.",
                "ERROR",
                1
            );
            return false;
        }
    }

    // SQL query to delete chat history from the original table
    $deleteQuery = "DELETE FROM chat_history WHERE user_id = ?";

    // Execute the query to delete chat history
    $deleteResult = executeNonQuery($deleteQuery, [$userId]);

    return $deleteResult;
}

/**
 * Sanitize user input to prevent security vulnerabilities such as SQL injection.
 *
 * This function takes a user input string and applies various sanitization techniques.
 * It trims the string, removes backslashes, and converts special characters to HTML entities.
 *
 * @param string $input The user input to be sanitized.
 * @return string The sanitized user input.
 * Note: This function aims to mitigate common vulnerabilities like SQL injection and Cross-Site Scripting (XSS) by sanitizing user input.
 */
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

/**
 * Validate user signup data including username, email, and password.
 *
 * This function takes the username, email, and password provided during user signup,
 * and validates each based on specific criteria.
 *
 * @param string $username The username to be validated.
 * @param string $email The email to be validated.
 * @param string $password The password to be validated.
 * @return string A string indicating the validation result.
 *
 * Note:
 * - Username should only contain alphanumeric characters and underscores.
 * - Email should be a valid email format.
 * - Password should be at least 8 characters long.
 */
function validateSignupInput($username, $email, $password, $verify_password)
{
    // Validate username
    if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return "Invalid_Usernamme";
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid_Email";
    }

    // Validate password
    if (empty($password) || strlen($password) < 8) {
        return "Invalid_Password_Length";
    }

    if ($password !== $verify_password) {
       
        return "Invalid_Password_Match"; // Exit early to prevent further processing
    }
    
    return "Validated";
}

/**
 * Validate either username or email based on the input for login.
 *
 * This function determines whether the provided input is a username or an email,
 * and validates it along with the password for user login.
 *
 * @param string $usernameOrEmail The username or email to be validated.
 * @param string $password The password to be validated.
 * @return string A string indicating the validation result.
 */
function validateUsernameOrEmail($usernameOrEmail, $password)
{
    // Determine if input is email or username
    $isEmail = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL);

    // Validate email
    if (
        $isEmail &&
        (empty($usernameOrEmail) ||
            !filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL))
    ) {
        return "Invalid_Email_Format";
    }

    if ($isEmail) {
        // Check if email exists in the database
        $query = "SELECT * FROM users WHERE email = ?";
        $params = [$usernameOrEmail];
        $result = executeQuery($query, $params);
        if (count($result) === 0) {
            return "Invalid_Email";
        }
    }

    // Validate username
    if (!$isEmail) {
        if (
            empty($usernameOrEmail) ||
            !preg_match('/^[a-zA-Z0-9_]+$/', $usernameOrEmail)
        ) {
            return "Invalid_Username_Format";
        }
        // Check if username exists in the database
        $query = "SELECT * FROM users WHERE username = ?";
        $params = [$usernameOrEmail];
        $result = executeQuery($query, $params);
        if (count($result) === 0) {
            return "Invalid_Username";
        }
    }

    // Validate password
    if (empty($password) || strlen($password) < 8) {
        return "Invalid_Password_Format";
    }

    return "Validated";
}

/**
 * Verify the provided password against the hashed password.
 *
 * This function uses PHP's password_verify to check if the provided password
 * matches the hashed password stored in the database.
 *
 * @param string $password The plaintext password provided by the user.
 * @param string $hashedPassword The hashed password stored in the database.
 * @return bool True if the password is verified, otherwise false.
 */
function verifyPassword($password, $hashedPassword)
{
    return password_verify($password, $hashedPassword);
}

/**
 * Authenticate a user and initiate a session.
 *
 * This function attempts to authenticate a user based on the provided username or email and password.
 * If authentication is successful, a session is initiated, and the user is redirected to the index page.
 * Otherwise, an error flag is set.
 *
 * @param string $usernameOrEmail The username or email address of the user.
 * @param string $password        The password of the user.
 *
 * @return void
 */
function userLogin($usernameOrEmail, $password)
{
    // Fetch user data based on username or email
    $user = getUserByUsernameOrEmail($usernameOrEmail);
    // $user = ARRAY[{ID} : 1 , {USERNAME} : Administrator ..]

    // Verify the provided password against the stored hash
    if (!verifyPassword($password, $user["password"])) {
        // Set an error flag for invalid credentials
        SystemFlag("Invalid Password", "Incorrect password.", "ERROR", 1);
    } else {
        // Set the user ID in the session to log in the user
        $_SESSION["user_id"] = $user["id"];

        // Redirect to the index page
        header("Location: index.php");
        exit();
    }
}


/**
 * Set a system flag for messaging.
 *
 * This function sets a system flag with a title, message, type, and visibility setting.
 * The flag is stored in the global $flags array.
 *
 * @param string $MessageTitle  The title of the message.
 * @param string $SystemMessage The content of the message.
 * @param string $Message_Type  The type of the message (e.g., "ERROR", "WARNING").
 * @param int    $UserFacing    Whether the message is user-facing (1) or not (0).
 *
 * @return void
 */
function SystemFlag($MessageTitle, $SystemMessage, $Message_Type, $UserFacing)
{


    if (!isset($_SESSION['flags'])) {
        $_SESSION['flags'] = [];
    }

    // Append the new flag to the session flags array
    $_SESSION['flags'][] = [
        "title" => $MessageTitle,
        "message" => $SystemMessage,
        "type" => $Message_Type,
        "userfacing" => $UserFacing,
    ];
}

/**
 * Estimate the number of tokens in a given text.
 *
 * This function estimates the number of tokens in a text string based on
 * either word count or character count, depending on the method specified.
 *
 * @param string $text The text to estimate tokens for.
 * @param string $method The method to use for token estimation ("average", "words", "chars", "max", "min").
 * @return int The estimated number of tokens.
 */
function estimate_tokens($text, $method = "max")
{
    // Initialize word and character counts
    $word_count = str_word_count($text);
    $char_count = strlen($text);

    // Calculate estimated token counts
    $tokens_count_word_est = $word_count / 0.75;
    $tokens_count_char_est = $char_count / 4.0;

    // Initialize output
    $output = 0;

    // Determine method for token estimation
    switch ($method) {
        case "average":
            $output = ($tokens_count_word_est + $tokens_count_char_est) / 2;
            break;
        case "words":
            $output = $tokens_count_word_est;
            break;
        case "chars":
            $output = $tokens_count_char_est;
            break;
        case "max":
            $output = max($tokens_count_word_est, $tokens_count_char_est);
            break;
        case "min":
            $output = min($tokens_count_word_est, $tokens_count_char_est);
            break;
        default:
            return "Invalid method. Use 'average', 'words', 'chars', 'max', or 'min'.";
    }

    return (int) $output;
}

/**
 * Make an API call to OpenAI to generate a response based on a prompt.
 *
 * This function takes a user prompt and an engine type, and makes an API call to OpenAI.
 * It returns the generated text as a response.
 *
 * @param string $prompt The user message prompt.
 * @param string $engine The engine to use for the API call.
 * @param array $messages Optional array of messages for conversational context.
 * @param string $personality The personality setting for the API call.
 * @return string The generated response from the API.
 */
function openaiApiCall($prompt, $messages, $engine, $personality)
{
    $api_key = OPENAI_API_KEY;
    $max_tokens = getTokenLimitByPersonalityId($engine);
    $temperature = TEMPERATURE;

    $token_estimate =
        $messages !== null
            ? estimate_tokens(json_encode($messages))
            : estimate_tokens($prompt);
    $adjusted_max_tokens = min($max_tokens, 1800 - $token_estimate); 

    // Check engine type using a switch-case for better structure
    switch (true) {
        case strpos($engine, "gpt-4") !== false:
        case strpos($engine, "gpt-3.5-turbo-16k") !== false:
            $data = json_encode([
                "model" => $engine,
                "messages" => $messages,
                "max_tokens" => $adjusted_max_tokens,
                "temperature" => $temperature,
            ]);
            $url = "https://api.openai.com/v1/chat/completions";
            break;
        default:
            $data = json_encode([
                "prompt" => $prompt,
                "max_tokens" => $adjusted_max_tokens,
                "temperature" => $temperature,
            ]);
            $url = "https://api.openai.com/v1/engines/$engine/completions";
            break;
    }

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key",
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL and close
    $response = curl_exec($ch);
    curl_close($ch);

    
    // Decode JSON response
    $response_data = json_decode($response, true);

    // Extract AI response and other details
    if (isset($response_data["choices"][0]["text"])) {
        $aiResponse = $response_data["choices"][0]["text"];
    } elseif (isset($response_data["choices"][0]["message"]["content"])) {
        $aiResponse = $response_data["choices"][0]["message"]["content"];
    } else {
        SystemFlag(
            "API ERROR",
            "API OUTPUT DOES NOT MEET REQUIREMENTS.",
            "ERROR",
            1
        );
        $aiResponse = $response;
        var_dump($aiResponse);
    }

    $aiResponse = str_replace("[Your Reply]", "", $aiResponse);
    $aiResponse = str_replace("[Your Response]", "", $aiResponse);
    $aiResponse = trim($aiResponse);

    //$promptTokens = $response_data["usage"]["prompt_tokens"];
    //$completionTokens = $response_data["usage"]["completion_tokens"];
    //$totalTokens = $response_data["usage"]["total_tokens"];
    //$model = $response_data["model"];
    var_dump(
        "<br> MAX TOKENS: " .
            $max_tokens .
            " TOKENS EST: " .
            $token_estimate .
            " Actuall Tokens: " .
            $response_data["usage"]["prompt_tokens"]
    );
    return $aiResponse . "<br>";
}
