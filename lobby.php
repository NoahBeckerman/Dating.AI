<?php
require_once "functions.php";
// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect("login.php");
}

// Check if a characterId was submitted
if (isset($_POST["character_ID"])) {
    $_SESSION["character_ID"] = $_POST["character_ID"];
    redirect("chatroom.php?character_ID=" . $_SESSION["character_ID"]);
}

// Display the lobby page content
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Dating.AI - Lobby</title>
    <?php include "head.php"; ?> <!-- Include the styling/scripts -->
  </head>
  <body>
    <?php include "header.php"; ?> <!-- Include the header -->
    <script src="SCRIPTS/lobby.js"></script>
    <main class="container">
        <h2 class="text-center">Browse Personalities</h2>
        <input type="text" id="searchBar" class="form-control" placeholder="Search Personalities...">

        <!-- Toggle Buttons for View -->
        <div class="view-toggle">
            <button id="listViewButton" class="btn btn-secondary">List View</button>
            <button id="cardViewButton" class="btn btn-secondary">Card View</button>
        </div>

        <div id="charactersContainer" class="characters-container card-view">
            <?php
            $characters = getCharacters();
            foreach ($characters as $character) {
                echo '<div class="character-card">';
                echo '<img src="' . $character["profile_picture"] . '" class="card-img-top" alt="Profile Picture">';
                echo '<div class="card-body">';
                echo "<h5 class='card-title'>" . $character["first_name"] . " " . $character["last_name"] . "</h5>";
                echo "<p class='card-text'>" . $character["bio"] . "</p>";
                echo "<p class='card-tags'>Schedule: " . $character["availability_schedule"] . "<br>Location: " . $character["current_location"] . "<br>Age: " . $character["age"] . "</p>";
                echo '<form method="post" action="lobby.php">';
                echo '<input type="hidden" name="character_ID" value="' . $character["id"] . '">';
                echo '<button type="submit" class="btn btn-primary">Chat</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
    <?php include "footer.php"; ?> <!-- Include the footer -->
  </body>
</html>
