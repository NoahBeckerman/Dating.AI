<?php
require_once 'functions.php';
// Check if user is already logged in, if yes, redirect to index page
if (isLoggedIn()) {
    redirect('index.php');
}
// Handle form submission for user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Validate the form data
    if (empty($email) || empty($username) || empty($password)) {
        // TODO: Handle empty form fields
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Insert the new user into the database
        $query = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
        executeNonQuery($query, [$email, $username, $hashedPassword]);
        // Redirect to the login page after successful registration
        redirect('login.php');
    }
}
// Display the signup page content
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <main>
        <h2>Create an Account</h2>
        <form action="signup.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Sign Up">
        </form>
    </main>
    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>