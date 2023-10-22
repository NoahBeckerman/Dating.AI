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
        SystemFlag('MissingCredentials', 'Please fill in all the requested feilds.', 'ERROR', 1);
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
    <title>Dating.AI - Home</title>
  
    <!-- Meta tags and title -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
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
    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>