<?php
if (isset($_SESSION['response_type'])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalTitle = document.querySelector('.modal-title');
                const modalBody = document.querySelector('.modal-body');
                modalTitle.textContent = '" . $_SESSION['response_title'] . "';
                modalBody.textContent = '" . $_SESSION['response_data'] . "';
                $('#messageModal').modal('show');
            });
          </script>";
    unset($_SESSION['response_type']);
    unset($_SESSION['response_title']);
    unset($_SESSION['response_data']);
}
?>