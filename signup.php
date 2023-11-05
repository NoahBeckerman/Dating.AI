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
    $verify_password = sanitizeInput($_POST["verify_password"]); // Retrieve the verify_password field

    $validationResult = validateSignupInput($username, $email, $password, $verify_password);

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

        if ($validationResult == "Invalid_Password_Length") {
            SystemFlag(
                "Invalid Password",
                "This password is invalid. Please use atleast 8 characters.",
                "ERROR",
                1
            );
           
    
        }
        if ($validationResult == "Invalid_Password_Match") {
        SystemFlag(
            "Password Mismatch",
            "The passwords you entered do not match. Please try again.",
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
    <main class="login-container">
    <div class="login-form">
        <h2>Create an Account</h2>
        <form action="signup.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="password">Password:</label>
            <input type="password" id="verify_password" name="verify_password" required><br>
            <input type="submit" value="Sign Up" class="btn-login">
        </form>
        <p>Already have an account? <a href="login.php">Sign In</a></p>
    </div>
</main>
<?php include "modal.php"; ?>
    <?php include "footer.php"; ?> <!-- Include the footer -->
</body>
</html>