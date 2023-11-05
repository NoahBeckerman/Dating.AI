<?php
require_once "functions.php";

// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect("login.php");
}

$userId = $_SESSION["user_id"];
if (isset($_GET["personalityId"])) {
    $personalityId = $_GET["personalityId"];
} elseif (isset($_SESSION["personalityId"])) {
    $personalityId = $_SESSION["personalityId"];
} else {
    // Handle the case where personalityId is not available in both GET and SESSION
    // Redirecting to a default page or showing an error message can be a solution
    // For now, I'll redirect to the login page as an example
    redirect("lobby.php");
}
// Handle form submission for sending messages
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["message"])) {
    $messageContent = $_POST["message"];
    sendMessage($userId, $personalityId, $messageContent);
}

// Fetch the personality details
$personalityDetails = getPersonalityById($personalityId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Chatroom</title>
    <?php include "head.php"; ?>

    <style>
        /* Existing styles from main.css and bootstrap.min.css */

        /* Chatroom Container Styles */
        .chatroom-container {
            display: flex;
            height: 100vh; /* Full viewport height */
            background-color: #343a40; /* Bootstrap dark background color */
        }

        /* Sidebar Styles */
        .chat-sidebar {
            width: 750px; /* Increased width for the sidebar */
            border-right: 1px solid #495057; /* Bootstrap dark border color */
            overflow-y: auto;
            background-color: #343a40; /* Bootstrap dark background color */
            padding: 0;
            margin: 0;
        }

        .chat-sidebar-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid #495057; /* Bootstrap dark border color */
            cursor: pointer;
            color: #adb5bd; /* Bootstrap dark text color */
        }

        .chat-sidebar-item:hover {
            background-color: #495057; /* Bootstrap dark hover color */
        }

        .chat-sidebar-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .chat-sidebar-name {
            flex: 1;
            font-weight: bold;
        }

        /* Chat Content Styles */
        .chat-content {
            flex-grow: 1;
            padding: 15px;
            background-color: #343a40; /* Bootstrap dark background color */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Chat Message Styles */
        .chat-message {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            max-width: 60%;
            clear: both;
            color: #adb5bd; /* Bootstrap dark text color */
        }

        .user-message {
            background-color: #495057; /* Bootstrap dark user message color */
            float: right;
        }

        .recipient-message {
            background-color: #212529; /* Bootstrap dark recipient message color */
            float: left;
        }

        /* Message Input Area Styles */
        .message-input-area {
            display: flex;
            background-color: #495057; /* Bootstrap dark input area color */
            padding: 10px;
            border-top: 1px solid #adb5bd; /* Bootstrap dark border color */
        }

        #message-input {
            flex-grow: 0.8; /* 80% width */
            padding: 10px;
            border-radius: 20px;
            border: none;
            margin-right: 10px;
            background-color: #343a40; /* Bootstrap dark input background color */
            color: #adb5bd; /* Bootstrap dark text color */
        }

        button {
            background-color: #007bff; /* Bootstrap primary button color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3; /* Bootstrap primary button hover color */
        }
    </style>

    <script>
        function loadChat(personalityId) {
            window.location.href = 'chatroom.php?user_id=<?php echo $userId; ?>&personality_id=' + personalityId;
        }
    </script>
</head>
<body>
<?php include "header.php"; ?>

<main class="chatroom-container">
    <div class="chat-sidebar">
        <?php
        $previousChats = getPreviousChats($userId);
        foreach ($previousChats as $chat) {
            echo '<div class="chat-sidebar-item" onclick="loadChat(' . $chat["personality_id"] . ')">';
            echo '<img src="' . $chat["profile_picture"] . '" alt="Avatar" class="chat-sidebar-avatar">';
            echo '<span class="chat-sidebar-name">' . $chat["first_name"] . ' ' . $chat["last_name"] . '</span>';
            echo '</div>';
        }
        ?>
    </div>

    <div class="chat-content">
        <div class="chat-header">
            <h2><?php echo $personalityDetails["first_name"] . " " . $personalityDetails["last_name"]; ?></h2>
        </div>
        <div class="chat-area">
            <?php
            $chatMessages = getChatMessages($userId, $personalityId);
            foreach ($chatMessages as $message) {
                if ($message["user_id"] == $userId ) {
                    echo '<div class="chat-message user-message">You: ' . $message["message"] . '</div>';
                    echo '<div class="chat-message recipient-message">' . $personalityDetails["first_name"] . ': ' . $message["response"] . '</div>';
                } 
            }
            ?>
        </div>
        <div class="message-input-area">
            <form action="chatroom.php" method="POST">
                <input type="text" id="message-input" name="message" placeholder="Type your message..." required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
</main>

<?php include "footer.php"; ?>
</body>
</html>
