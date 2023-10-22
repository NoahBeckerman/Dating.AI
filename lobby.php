<?php
require_once 'functions.php';
// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect('login.php');
}
// Display the lobby page content
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Lobby</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <main>
        <h2>Browse Personalities</h2>
        <div class="personalities">
            <?php
            // Display the list of personalities
            $personalities = getPersonalities();
            foreach ($personalities as $personality) {
                echo '<div class="personality">';
                echo '<img src="' . $personality['profile_picture'] . '" alt="Profile Picture">';
                echo '<h3>' . $personality['first_name'] . ' ' . $personality['last_name'] . '</h3>';
                echo '<p>' . $personality['description'] . '</p>';
                echo '<button onclick="selectPersonality(' . $personality['id'] . ')">Chat</button>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>