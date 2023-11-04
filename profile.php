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

   // If update_account button was clicked
   if (isset($_POST["update_account"])) {
    $fieldsToUpdate = [];
    $params = [];

    // Check each field for changes
    foreach (['username', 'email', 'password', 'age', 'preferences'] as $field) {
        if ($_POST[$field] !== $user[$field]) {
            $fieldsToUpdate[] = "$field = ?";
            $params[] = $_POST[$field];
        }
    }

    // If there are fields to update
    if (!empty($fieldsToUpdate)) {
        $query = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE id = ?";
        $params[] = $userId;

        executeNonQuery($query, $params);
       // Set a success message to be displayed in modal.php
    SystemFlag(
      "UpdateAccountSuccess",
      "Change complete.",
      "SUCCESS",
      1
    );
  
  
    }
}

// If update_billing button was clicked
if (isset($_POST["update_billing"])) {
    $fieldsToUpdate = [];
    $params = [];

    // Check each field for changes
    foreach (['addr1', 'addr2', 'zip', 'state', 'country', 'first_name', 'last_name'] as $field) {
        if ($_POST[$field] !== $user[$field]) {
            $fieldsToUpdate[] = "$field = ?";
            $params[] = $_POST[$field];
        }
    }

    // If there are fields to update
    if (!empty($fieldsToUpdate)) {
        $query = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE id = ?";
        $params[] = $userId;

        executeNonQuery($query, $params);
        
           // Set a success message to be displayed in modal.php
    SystemFlag(
      "UpdateBillingSuccess",
      "Change complete.",
      "SUCCESS",
      1
  );

    }
}

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
  $username = $_POST['username'];
  $hashedFolderName = md5($username);
  $targetFolder = "USER_DATA/" . $hashedFolderName;
  
  if (!file_exists($targetFolder)) {
      mkdir($targetFolder, 0777, true);
  }
  
  $timestamp = time();
  $fileExtension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
  $newFileName = $timestamp . "." . $fileExtension;
  
  move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFolder . "/" . $newFileName);
  
  // Update the 'profile_picture' column in the database
  $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
  $params = [$newFileName, $userId];
  executeNonQuery($query, $params);
}


}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    
    <?php include "head.php"; ?> <!-- Include the styling/scripts -->

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
                 Username <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($user['username']); ?>" required> 
                 Email <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>"required> 
                 Password <input type="password" name="password" class="form-control" placeholder="Password" required>
                 Age <input type="text" name="age" class="form-control" placeholder="" value="<?php echo htmlspecialchars($user['age']); ?>" > 
                 Preferences <input type="text" name="preferences" class="form-control" placeholder="" value="<?php echo htmlspecialchars($user['preferences']); ?>"> 
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
              <form action="profile.php" method="post" class="form-signin"> 
                Address Line 1 <input type="text" name="addr1" class="form-control" placeholder="Address Line 1" value="<?php echo htmlspecialchars($user['addr1']); ?>">
               Address Line 2 <input type="text" name="addr2" class="form-control" placeholder="Address Line 2" value="<?php echo htmlspecialchars($user['addr2']); ?>"> 
               ZIP Code <input type="text" name="zip" class="form-control" placeholder="ZIP Code" value="<?php echo htmlspecialchars($user['zip']); ?>">
                State <input type="text" name="state" class="form-control" placeholder="State" value="<?php echo htmlspecialchars($user['state']); ?>">
                 Country <input type="text" name="country" class="form-control" placeholder="Country" value="<?php echo htmlspecialchars($user['country']); ?>"> 
                 First Name <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo htmlspecialchars($user['first_name']); ?>"> 
                 Last Name <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
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

