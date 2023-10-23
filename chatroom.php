<?php
require_once "functions.php";

// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect("login.php");
}

// Get the selected personality ID from URL or session
$personalityId = isset($_GET["personalityId"])
    ? $_GET["personalityId"]
    : $_SESSION["personalityId"];

// If no personality ID is found, redirect to lobby
if (!$personalityId) {
    redirect("lobby.php");
}

// Handle form submission for sending messages
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $message = $_POST["message"];
    // Send the message and get the response
    $userId = $_SESSION["user_id"];
    $response = sendMessage($userId, $message, $personalityId);
    // Store the chat record in the database
    storeChatRecord($userId, $personalityId, $message, $response);
}

// Display the chatroom page content
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Dating.AI - Chatroom</title>
    <!-- Meta tags and title -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body> <?php include "header.php"; ?>
    <!-- Include the header -->
    <main>
      <div class="sidebar">
        <h2>Previous Chats</h2>
        <ul> <?php
                // Display the list of previous chats
                $userId = $_SESSION["user_id"];
                $previousChats = getPreviousChats($userId);
                foreach ($previousChats as $chat) {
                    echo '
							<li>
								<a href="chatroom.php?personalityId=' .
                        $chat["personality_id"] .
                        '">' .
                        $chat["first_name"] .
                        " " .
                        $chat["last_name"] .
                        "</a>
							</li>";
                }
                ?> </ul>
      </div> <?php include "modal.php"; ?> <div class="chat-window">
        <h2>Open Conversation</h2>
        <div class="chat-messages"> <?php
                // Display the chat messages here
                $chatMessages = getChatMessages($userId, $personalityId);
                foreach ($chatMessages as $message) {
                    echo '
							<div class="chat-message">' .
                        $message["message"] .
                        "</div>";
                    echo '
							<div class="chat-response">' .
                        $message["response"] .
                        "</div>";
                }
                ?> </div>
        <div class="chat-input">
          <form action="chatroom.php" method="POST">
            <input type="text" id="message-input" name="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
          </form>
        </div>
      </div>
    </main> <?php include "footer.php"; ?>
    <!-- Include the footer -->
  </body>
</html>