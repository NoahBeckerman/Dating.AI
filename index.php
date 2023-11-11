<?php
require "functions.php";
// Check if user is logged in, otherwise redirect to login page
if (!isLoggedIn()) {
    redirect('login.php');
}

//resetUserPassword(10, "MoneyMan1$");
// Display the index page content
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Dating.AI - Home</title>
    
    <?php include "head.php"; ?> <!-- Include the styling/scripts -->

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