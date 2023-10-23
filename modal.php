<style>
  .popup {
    display: none;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
  }

  .popup.show {
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn 1s;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  .popup-header {
    border-top: 3px solid red;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .popup-title {
    font-weight: bold;
  }

  .popup-type {
    font-size: 0.8em;
    margin-right: 10px;
  }

  .popup-message {
    text-align: center;
    margin: 10px 0;
  }

  .popup-close {
    cursor: pointer;
  }
</style>
<!-- The popup container -->
<div class="popup" id="messagePopup">
  <!-- The popup content -->
  <div class="popuptext bg-dark text-white p-4 rounded shadow-lg" style="border-top: 3px solid red;">
    <!-- Header with title and close button -->
    <div class="d-flex justify-content-between align-items-center">
      <div id="messagePopupTitle" class="font-weight-bold"></div>
      <span class="text-white" onclick="closePopup()">x</span>
    </div>
    <!-- Message content -->
    <div class="text-center my-3" id="messagePopupText"></div>
    <!-- Acknowledge button -->
    <button class="btn btn-success mt-2 float-right" onclick="closePopup()">Acknowledge</button>
  </div>
</div>


<script>
function closePopup() {
    document.getElementById('messagePopup').classList.remove('show');
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

<?php // Check if there are any flags to display
if (!empty($flags)) {
    echo "<script>";
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    // Loop through each flag
    foreach ($flags as $flag) {
        // Only display the flag if it's user-facing
        if ($flag["userfacing"] == 1) {
            echo "document.getElementById('messagePopupTitle').textContent = '{$flag["title"]}';
                  document.getElementById('messagePopupText').textContent = '{$flag["message"]}';
                  document.getElementById('messagePopup').classList.add('show');";
        } else {
            // Log non-user-facing flags to the console
            echo "console.log('{$flag["type"]}: {$flag["title"]} - {$flag["message"]}');";
        }
    }
    echo "});";
    echo "</script>";
} ?>





