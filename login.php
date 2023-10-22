<?php
require_once 'functions.php';

// Check if user is already logged in, if yes, redirect to index page
if (isLoggedIn()) {
    redirect('index.php');
}
// Handle form submission for user login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];
    userLogin($usernameOrEmail, $password);
}

// Display the login page content
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <main>
        <h2>Sign In</h2>
        <form action="login.php" method="POST">
            <label for="username_or_email">Username or Email:</label>
            <input type="text" id="username_or_email" name="username_or_email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Sign In">
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </main>



    
    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>