<?php
require_once "functions.php";
// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect("login.php");
}

// Check if a personalityId was submitted
// Check if a personalityId was submitted
if (isset($_POST["personalityId"])) {
    $_SESSION["personalityId"] = $_POST["personalityId"];
    redirect("chatroom.php?personalityId=" . $_SESSION["personalityId"]);
}

// Display the lobby page content
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Dating.AI - Lobby</title>
    <!-- Meta tags and title -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <?php include "header.php"; ?> <!-- Include the header -->
    <main>
        <h2>Browse Personalities</h2>
        <div class="personalities">
    <?php
    // Display the list of personalities
    $personalities = getPersonalities();
    foreach ($personalities as $personality) {
        echo '<div class="personality">';
        echo '<img src="' .
            $personality["profile_picture"] .
            '" alt="Profile Picture">';
        echo "<h3>" .
            $personality["first_name"] .
            " " .
            $personality["last_name"] .
            "</h3>";
        echo "<p>" . $personality["description"] . "</p>";
        echo '<form method="post" action="lobby.php">';
        echo '<input type="hidden" name="personalityId" value="' .
            $personality["id"] .
            '">';
        echo '<button type="submit">Chat</button>';
        echo "</form>";
        echo "</div>";
    }
    ?>
        </div>
    </main>
    <?php include "footer.php"; ?> <!-- Include the footer -->
  </body>
</html>