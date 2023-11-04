<?php 
include_once "functions.php";


// Check if the clearFlags request is made
if (isset($_GET['clearFlags']) && $_GET['clearFlags'] == 'true') {
    $_SESSION['flags'] = []; // Clear the flags from the session
    header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirect back to the previous page
    exit();
}
?>

<!-- The popup container -->
<div class="popup" id="messagePopup">
  <!-- The popup content -->
  <div class="popuptext bg-dark text-white p-4 rounded shadow-lg" style="border-top: 3px solid red;">
    <!-- Header with title and close button -->
    <div class="d-flex justify-content-between align-items-center">
      <div id="messagePopupTitle" class="font-weight-bold"></div>
      <span class="text-white" onclick="clearFlagsAndRefresh()">   x</span>
    </div>
    <!-- Message content -->
    <div class="text-center my-3" id="messagePopupText"></div>
    <!-- Acknowledge button -->
    <button class="btn btn-success mt-2 float-right" onclick="clearFlagsAndRefresh()">Acknowledge</button>
  </div>
</div>


<script>
function clearFlagsAndRefresh() {
    window.location.href = 'modal.php?clearFlags=true'; // Redirect to modal.php with clearFlags query parameter
}

function setColorBasedOnType(type) {
    let color;
    switch (type) {
        case 'Message':
            color = 'yellow';
            break;
        case 'Success':
            color = 'green';
            break;
        case 'Warning':
            color = 'orange';
            break;
        case 'Error':
            color = 'red';
            break;
        default:
            color = 'red';
    }
    document.getElementById('messagePopupType').style.borderTopColor = color;
}
</script>

<?php 
if (!empty($_SESSION['flags'])) {
    echo "<script>";
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    foreach ($_SESSION['flags'] as $flag) {
        if ($flag["userfacing"] == 1) {
            echo "document.getElementById('messagePopupTitle').textContent = '{$flag["title"]}';
                  document.getElementById('messagePopupText').textContent = '{$flag["message"]}';
                  document.getElementById('messagePopup').classList.add('show');";
        } else {
            echo "console.log('{$flag["type"]}: {$flag["title"]} - {$flag["message"]}');";
        }
    }
    echo "});";
    echo "</script>";
}
?>