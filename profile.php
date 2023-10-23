<?php
// profile.php

// Include necessary files
include_once "functions.php";

// Check if the user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$userId = $_SESSION["user_id"];

// Fetch user details
$user = getUserById($userId);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Clear chat history
    if (isset($_POST["clear_chat_history"])) {
        $result = migrateAndDeleteChatHistory($userId);
        if (!$result) {
            // Set an error message to be displayed in modal.php
            SystemFlag(
                "ClearHistoryFailure",
                "Failed to clear chat history.",
                "ERROR",
                1
            );
        } else {
            SystemFlag(
                "ClearHistorySuccess",
                "Successfully cleared chat history.",
                "SUCCESS",
                1
            );
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <!-- Meta tags and title -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body> <?php include "header.php"; ?> <div class="container mt-5 bg-dark text-white">
      <h1 class="text-center">Your Profile</h1>
      <div id="accordion">
        <div class="card bg-secondary">
          <div class="card-header">
            <a class="card-link text-white" data-toggle="collapse" href="#collapseOne"> Account Information </a>
          </div>
          <div id="collapseOne" class="collapse show" data-parent="#accordion">
            <div class="card-body">
              <form action="profile.php" method="post" enctype="multipart/form-data" class="form-signin">
                 Username <input type="text" name="username" class="form-control" placeholder="Username" required> 
                 Email <input type="email" name="email" class="form-control" placeholder="Email" required> 
                 Password <input type="password" name="password" class="form-control" placeholder="Password" required>
                 Age <input type="text" name="age" class="form-control" placeholder="" > 
                 Preferences <input type="text" name="Preferences" class="form-control" placeholder="" > 
                  Profile Picture <div class="custom-file">
                  <input type="file" class="custom-file-input" id="profile_picture" name="profile_picture">
                  <label class="custom-file-label" for="profile_picture">Choose Profile Picture</label>
                </div>
                <button type="submit" name="update_account" class="btn btn-primary">Update Account</button>
              </form>
            </div>
          </div>
        </div>
        <!-- Billing Information -->
        <div class="card bg-secondary">
          <div class="card-header">
            <a class="card-link text-white" data-toggle="collapse" href="#collapseTwo"> Billing Information </a>
          </div>
          <div id="collapseTwo" class="collapse" data-parent="#accordion">
            <div class="card-body">
              <form action="profile.php" method="post" class="form-signin"> Address Line 1 <input type="text" name="addr1" class="form-control" placeholder="Address Line 1"> Address Line 2 <input type="text" name="addr2" class="form-control" placeholder="Address Line 2"> ZIP Code <input type="text" name="zip" class="form-control" placeholder="ZIP Code"> State <input type="text" name="state" class="form-control" placeholder="State"> Country <input type="text" name="country" class="form-control" placeholder="Country"> First Name <input type="text" name="first_name" class="form-control" placeholder="First Name"> Last Name <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                <button type="submit" name="update_billing" class="btn btn-primary">Update Billing</button>
              </form>
            </div>
          </div>
        </div>
        <!-- Subscription Information -->
        <div class="card bg-secondary">
          <div class="card-header">
            <a class="card-link text-white" data-toggle="collapse" href="#collapseThree"> Subscription Information </a>
          </div>
          <div id="collapseThree" class="collapse" data-parent="#accordion">
            <div class="card-body">
              <!-- Placeholder for Subscription Information -->
            </div>
          </div>
        </div>
        <!-- Statistics -->
        <div class="card bg-secondary">
          <div class="card-header">
            <a class="card-link text-white" data-toggle="collapse" href="#collapseFour"> Statistics </a>
          </div>
          <div id="collapseFour" class="collapse" data-parent="#accordion">
            <div class="card-body">
              <!-- Placeholder for Statistics -->
            </div>
          </div>
        </div>
      </div>
      <!-- End of Accordion -->
    </div>

<?php include "modal.php"; ?>


<form action="profile.php" method="post" class="mt-3">
    <button type="submit" name="clear_chat_history" class="btn btn-lg btn-danger btn-block">Clear Chat History</button>
</form>

<?php include "footer.php"; ?>


</body>
</html>

