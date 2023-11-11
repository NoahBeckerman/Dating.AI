<?php
require_once "functions.php";
// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect("login.php");
}

// Check if a characterId was submitted
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
        <div id="carouselContainer" class="carousel slide hidden" data-ride="carousel">
            <div class="carousel-inner">
                <!-- Carousel content will be populated here -->
            </div>
        </div>
        <div id="gridContainer" class="row">
            <?php
            // Assuming getPersonalities() returns an array of personalities
            $characters = getCharacters();
            foreach ($characters as $character) {
                echo '<div class="col-md-4 personality">';
                echo '<div class="lobby-card">';
                echo '<img src="' . $character["profile_picture"] . '" class="lobby-card-img-top" alt="Profile Picture">';
                echo '<div class="lobby-card-body">';
                echo "<h5 class='lobby-card-title'>" . $character["first_name"] . " " . $character["last_name"] . "</h5>";
                echo "<p class='lobby-card-text'>" . $character["bio"] . "</p>";
                echo '<form method="post" action="lobby.php">';
                echo '<input type="hidden" name="character_ID" value="' . $character["id"] . '">';
                echo '<button type="submit" class="btn btn-primary">Chat</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
    <?php include "footer.php"; ?> <!-- Include the footer -->
  </body>
</html>