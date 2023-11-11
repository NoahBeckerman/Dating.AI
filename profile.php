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

$folderName = md5($user["username"]);
$pathToFolder = "USER_DATA/" . $folderName;

$mostRecentPic = null;
if (is_dir($pathToFolder)) {
    $files = scandir($pathToFolder);
    rsort($files); // Sort files in descending order

    foreach ($files as $file) {
        if (strpos($file, ".") !== 0) {
            // Check if file is not a directory (like '.' or '..')
            $mostRecentPic = $pathToFolder . "/" . $file;
            break;
        }
    }
}

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
        foreach (
            ["username", "email", "password", "age", "preferences"]
            as $field
        ) {
            if ($_POST[$field] !== $user[$field]) {
                if ($field === "password") {
                    // Hash the password before updating
                    $hashedPassword = password_hash(
                        $_POST[$field],
                        PASSWORD_DEFAULT
                    );
                    $fieldsToUpdate[] = "$field = ?";
                    $params[] = $hashedPassword;
                } else {
                    $fieldsToUpdate[] = "$field = ?";
                    $params[] = $_POST[$field];
                }
            }
        }

        // If there are fields to update
        if (!empty($fieldsToUpdate)) {
            $query =
                "UPDATE users SET " .
                implode(", ", $fieldsToUpdate) .
                " WHERE id = ?";
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
        foreach (
            [
                "addr1",
                "addr2",
                "zip",
                "state",
                "country",
                "first_name",
                "last_name",
            ]
            as $field
        ) {
            if ($_POST[$field] !== $user[$field]) {
                $fieldsToUpdate[] = "$field = ?";
                $params[] = $_POST[$field];
            }
        }

        // If there are fields to update
        if (!empty($fieldsToUpdate)) {
            $query =
                "UPDATE users SET " .
                implode(", ", $fieldsToUpdate) .
                " WHERE id = ?";
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

    if (
        isset($_FILES["profile_picture"]) &&
        $_FILES["profile_picture"]["error"] == 0
    ) {
        $username = $_POST["username"];
        $hashedFolderName = md5($username);
        $targetFolder = "USER_DATA/" . $hashedFolderName;

        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }

        $timestamp = time();
        $fileExtension = pathinfo(
            $_FILES["profile_picture"]["name"],
            PATHINFO_EXTENSION
        );
        $newFileName = $timestamp . "." . $fileExtension;

        move_uploaded_file(
            $_FILES["profile_picture"]["tmp_name"],
            $targetFolder . "/" . $newFileName
        );

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
  <body> <?php include "header.php"; ?> <div class="profile-tiles">
  
  <!-- User Information Tile -->
  <div class="tile">
  <div class="tile-header">User Information</div>
  <div class="tile-content">
    <form action="profile.php" method="post" enctype="multipart/form-data" class="form-signin">
       <div class="form-group">
         <label for="username">Username</label>
         <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($user["username"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="email">Email</label>
         <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($user["email"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="password">Password</label>
         <input type="password" id="password" name="password" class="form-control" placeholder="Password">
       </div>
       
       <div class="form-group">
         <label for="age">Age</label>
         <input type="text" id="age" name="age" class="form-control" placeholder="Age" value="<?php echo htmlspecialchars($user["age"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="preferences">Preferences</label>
         <input type="text" id="preferences" name="preferences" class="form-control" placeholder="Preferences" value="<?php echo htmlspecialchars($user["preferences"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="profile_picture">Profile Picture</label>
         <?php if ($mostRecentPic): ?>
           <div class="tile-img-container">
             <img src="<?php echo $mostRecentPic; ?>" alt="Current Profile Picture" class="tile-img-thumbnail">
           </div>
         <?php endif; ?>
         <input type="file" id="profile_picture" name="profile_picture" class="form-control-file">
       </div>
       
       <button type="submit" name="update_account" class="btn btn-primary">Update Account</button>
    </form>
  </div>
</div>

  
  <!-- Billing Information Tile -->
  <div class="tile">
  <div class="tile-header">Billing Information</div>
  <div class="tile-content">
    <form action="profile.php" method="post" class="form-signin">
       <div class="form-group">
         <label for="addr1">Address Line 1</label>
         <input type="text" id="addr1" name="addr1" class="form-control" placeholder="Address Line 1" value="<?php echo htmlspecialchars($user["addr1"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="addr2">Address Line 2</label>
         <input type="text" id="addr2" name="addr2" class="form-control" placeholder="Address Line 2" value="<?php echo htmlspecialchars($user["addr2"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="zip">ZIP Code</label>
         <input type="text" id="zip" name="zip" class="form-control" placeholder="ZIP Code" value="<?php echo htmlspecialchars($user["zip"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="state">State</label>
         <input type="text" id="state" name="state" class="form-control" placeholder="State" value="<?php echo htmlspecialchars($user["state"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="country">Country</label>
         <input type="text" id="country" name="country" class="form-control" placeholder="Country" value="<?php echo htmlspecialchars($user["country"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="first_name">First Name</label>
         <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First Name" value="<?php echo htmlspecialchars($user["first_name"]); ?>">
       </div>
       
       <div class="form-group">
         <label for="last_name">Last Name</label>
         <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo htmlspecialchars($user["last_name"]); ?>">
       </div>
       
       <button type="submit" name="update_billing" class="btn btn-primary">Update Billing</button>
    </form>
  </div>
</div>

  
  <!-- Subscription Information Tile -->
  <div class="tile">
    <div class="tile-header">Subscription Information</div>
    <div class="tile-content">
      <!-- Subscription information content goes here -->
    </div>
  </div>
  
  <!-- Statistics Tile -->
  <div class="tile">
    <div class="tile-header">Statistics</div>
    <div class="tile-content">
      <!-- Statistics content goes here -->
    </div>
  </div>

</div>
<?php //include "modal.php"; ?>


<form action="profile.php" method="post" class="mt-3">
    <button type="submit" name="clear_chat_history" class="btn btn-lg btn-danger btn-block">Clear Chat History</button>
</form>
</body>
</html>
