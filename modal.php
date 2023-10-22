<style>
    .popup {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
        z-index: 1000;
    }
    .popup.show {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 1s;
    }
    @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
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



<div class="popup" id="messagePopup">
  <div class="popuptext bg-dark text-white p-4 rounded shadow-lg">
    <div class="popup-header">
      <div class="popup-type" id="messagePopupType"></div>
      <div class="popup-title" id="messagePopupTitle"></div>
      <span class="popup-close text-white" onclick="closePopup()">x</span>
    </div>
    <div class="popup-message" id="messagePopupText"></div>
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


<?php
if (!empty($flags)) {
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    foreach ($flags as $flag) {
        if ($flag['userfacing'] == 1) {
            echo "document.getElementById('messagePopupTitle').textContent = '{$flag['title']}';
                  document.getElementById('messagePopupText').textContent = '{$flag['message']}';
                  document.getElementById('messagePopupType').textContent = '{$flag['type']}';
                  document.getElementById('messagePopup').classList.add('show');
                  setColorBasedOnType('{$flag['type']}');";
        } else {
            echo "console.log('{$flag['type']}: {$flag['title']} - {$flag['message']}');";
        }
    }
    echo '});';
    echo '</script>';
}
?>



