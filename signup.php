<?php
require_once "functions.php";
// Check if user is already logged in, if yes, redirect to index page
if (isLoggedIn()) {
    redirect("index.php");
}

// Handle form submission for user registration
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form data
    $username = sanitizeInput($_POST["username"]);
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);

    $validationResult = validateSignupInput($username, $email, $password);

    if ($validationResult !== "Validated") {
        if ($validationResult == "Invalid_Username") {
            SystemFlag(
                "Invalid Username",
                "This username is invalid. Please use atleast 4 characters.",
                "ERROR",
                1
            );
        }

        if ($validationResult == "Invalid_Email") {
            SystemFlag(
                "Invalid Email",
                "This Email is invalid. Please use a REAL email.",
                "ERROR",
                1
            );
        }

        if ($validationResult == "Invalid_Password") {
            SystemFlag(
                "Invalid Password",
                "This password is invalid. Please use atleast 8 characters.",
                "ERROR",
                1
            );
        }
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Insert the new user into the database
        $query =
            "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
        executeNonQuery($query, [$email, $username, $hashedPassword]);
        // Redirect to the login page after successful registration
        redirect("login.php");
    }
}

// Display the signup page content
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Home</title>
  
    <?php include "head.php"; ?> <!-- Include the styling/scripts -->

</head>
<body>
    <?php include "header.php"; ?> <!-- Include the header -->
    <main>
        <h2>Create an Account</h2>
        <form action="signup.php" method="POST">
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="password">Password:</label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
            <input type="submit" value="Sign Up">
        </form>
    </main>
    <?php include "footer.php"; ?> <!-- Include the footer -->
</body>
</html>