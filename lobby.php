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
            <button id="list-view-btn" class="btn btn-secondary">List View</button>
            <button id="card-view-btn" class="btn btn-secondary">Card View</button>
        </div>

      <br>

        <div id="charactersContainer" class="characters-container card-view">
    <?php
    $characters = getCharacters(); // Function to fetch characters from 'users' table
    foreach ($characters as $character) {
        echo '<div class="character-card">';
        echo '<img src="' . htmlspecialchars($character["profile_picture"]) . '" class="card-img-top" alt="Profile Picture">';
        echo '<div class="card-body">';
        echo "<h5 class='card-title'>" . htmlspecialchars($character["first_name"]) . " " . htmlspecialchars($character["last_name"]) . "</h5>";
        echo "<p class='bio'>" . htmlspecialchars($character["bio"]) . "</p>";
        // Display availability schedule, location, and age in bubbles
        echo '<div class="card-tags">';

        // Availability Schedule - assuming it is JSON
        $availabilitySchedule = json_decode($character["availability_schedule"], true);
        if ($availabilitySchedule) {
            echo '<div class="tag-bubble">Schedule:';
            foreach ($availabilitySchedule as $key => $value) {
                echo '<div class="mini-bubble">' . htmlspecialchars($key) . ': ' . htmlspecialchars($value) . '</div>';
            }
            echo '</div>'; // Close schedule bubble
        }

        // Location
        if (!empty($character["current_location"])) {
            echo '<div class="tag-bubble">';
            echo 'Location: ' . htmlspecialchars($character["current_location"]);
            echo '</div>'; // Close location bubble
        }

        // Age
        if (!empty($character["age"])) {
            echo '<div class="tag-bubble">';
            echo 'Age: ' . htmlspecialchars($character["age"]);
            echo '</div>'; // Close age bubble
        }

        echo '</div>'; // Close card-tags
        echo '<form method="post" action="lobby.php">';
        echo '<input type="hidden" name="character_ID" value="' . $character["id"] . '">';
        echo '<button type="submit" class="btn btn-primary">Chat</button>';
        echo '</form>';
        echo '</div>'; // Close card-body
      
        echo '</div>'; // Close character-card
    }
    ?>
</div>
    </main>
    <?php include "footer.php"; ?> <!-- Include the footer -->
  </body>
</html>
