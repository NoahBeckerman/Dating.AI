<?php
require_once "functions.php";

$userId = $_SESSION["user_id"];
// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect("login.php");
}

if (subscribed($userId) == false) {
    redirect("lobby.php");
}


if (isset($_GET["character_ID"])) {
    $characterID = $_GET["character_ID"];
    $_SESSION["character_ID"] = $characterID;
} elseif (isset($_SESSION["character_ID"])) {
    $characterID = $_SESSION["character_ID"];
} else {
    // Handle the case where personalityId is not available in both GET and SESSION
    // Redirecting to a default page or showing an error message can be a solution
    // For now, I'll redirect to the login page as an example
    redirect("lobby.php");
}
// Handle form submission for sending messages
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["message"])) {
    $message = $_POST["message"];
    $response = sendMessage($userId, $message, $characterID);
    storeChatRecord($userId, $characterID, $message, $response);
}

// Fetch the personality details
$characterDetails = getCharacterById($characterID);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Chatroom</title>
    <?php include "head.php"; ?>

    <script>
        function loadChat(characterID) {
            window.location.href = 'chatroom.php?user_id=<?php echo $userId; ?>&character_id=' + characterID;
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
        echo '<div class="profile-card" onclick="loadChat(' . $chat["characters_id"] . ')">';
        echo '<img src="' . $chat["profile_picture"] . '" alt="Avatar" class="profile-picture">';
        echo '<div class="profile-info">';
        echo '<div class="profile-name">' . $chat["first_name"] . ' ' . $chat["last_name"] . '</div>';
        echo '<div class="profile-status">Status Message</div>'; // Replace 'Status Message' with actual status if available
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>


    <div class="chat-content">
        <div class="chat-header">
            <h2><?php echo $characterDetails["first_name"] . " " . $characterDetails["last_name"]; ?></h2>
        </div>

        <div class="chat-area">
    <?php
    $chatMessages = getChatMessages($userId, $characterID);
    foreach ($chatMessages as $message) {
        if ($message["user_id"] == $userId) {
            echo '<div class="chat-message-container user-message-container">';
            echo '<div class="chat-message user-message">You: ' . $message["message"] . '</div>';
            echo '</div>';
       
            echo '<div class="chat-message-container recipient-message-container">';
            echo '<div class="chat-message recipient-message">' . $message["response"] . '</div>';
            echo '</div>';
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
<script>
// Function to scroll to the bottom of the chat area
function scrollToBottom() {
    var chatArea = document.querySelector('.chat-area');
    chatArea.scrollTop = chatArea.scrollHeight;
}

// Scroll to bottom on page load
window.onload = scrollToBottom;

// MutationObserver to detect changes in the chat area and scroll
var observer = new MutationObserver(scrollToBottom);
var config = { childList: true };
var target = document.querySelector('.chat-area');
observer.observe(target, config);

// Ensure scrolling after sending a message
document.querySelector('.message-input-area form').addEventListener('submit', function() {
    setTimeout(scrollToBottom, 500); // Delay to allow for message rendering
});

</script>