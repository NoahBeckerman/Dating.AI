<?php
require_once 'functions.php';

// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect('login.php');
}
// Display the index page content
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
  <body> <?php include 'header.php'; ?>
    <!-- Include the header -->
    <main>
      <h2>Welcome to Dating.AI</h2>
      <p>This is the home page of Dating.AI. Start by browsing the available personalities and find someone interesting to chat with.</p>
    </main> <?php include 'footer.php'; ?>
    <!-- Include the footer -->
  </body>
</html>