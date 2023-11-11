<?php
require_once "functions.php";

// Check if user is already logged in, if yes, redirect to index page
if (isLoggedIn()) {
    redirect("index.php");
}
// Handle form submission for user login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameOrEmail = sanitizeInput($_POST["username_or_email"]);
    $password = sanitizeInput($_POST["password"]);

    $validationResult = validateUsernameOrEmail($usernameOrEmail, $password);
    if ($validationResult !== "Validated") {
        if ($validationResult == "Invalid_Username") {
            SystemFlag(
                "Invalid Username",
                "This username is does not exist.",
                "ERROR",
                1
            );
        }

        if ($validationResult == "Invalid_Username_Format") {
            SystemFlag(
                "Invalid Username",
                "This username format is incorrect.",
                "ERROR",
                1
            );
        }

        if ($validationResult == "Invalid_Email") {
            SystemFlag(
                "Invalid Email",
                "This Email is does not exist.",
                "ERROR",
                1
            );
        }

        if ($validationResult == "Invalid_Email_Format") {
            SystemFlag(
                "Invalid Email",
                "This Email format is incorrect.",
                "ERROR",
                1
            );
        }

        if ($validationResult == "Invalid_Password_Format") {
            SystemFlag(
                "Invalid Password",
                "This password format is incorrect, please use atleast 8 characters.",
                "ERROR",
                1
            );
        }
    } else {
        userLogin($usernameOrEmail, $password);
    }
}

// Display the login page content
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Login</title>

<?php include "head.php"; ?> <!-- Include the styling/scripts -->

</head>
<body>
    <?php include "header.php"; ?> <!-- Include the header -->
    <main class="login-container">
    <div class="login-form">
        <h2>Sign In</h2>
        <form action="login.php" method="POST">
            <label for="username_or_email">Username or Email:</label>
            <input type="text" id="username_or_email" name="username_or_email" required><br>
            <div class="password-container">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>  
            <span onclick="togglePasswordVisibility()" class="toggle-password">&#128065;</span> <!-- Eye icon -->
</div>
<br>
            
            <input type="submit" value="Sign In" class="btn-login">
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
    </main>

    <?php include "modal.php"; ?>
    <?php include "footer.php"; ?> <!-- Include the footer -->
</body>
</html>

<script src="SCRIPTS/auth.js"></script>