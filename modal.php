<script>
    /* Custom Modal Styles */
.custom-modal-content {
    background-color: #f9f9f9;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.custom-modal-header {
    background-color: #343a40;
    color: #ffffff;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.custom-modal-title {
    font-weight: bold;
}

.custom-modal-body {
    color: #333;
    padding: 20px;
}

.custom-modal-footer {
    background-color: #343a40;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
}

.custom-modal-btn {
    background-color: #ffffff;
    color: #343a40;
}
</script>

<!-- modal.php -->
<!-- Bootstrap Modal for Messages -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal-content">
      <div class="modal-header custom-modal-header">
        <h5 class="modal-title custom-modal-title" id="messageModalLabel">Message</h5>
        <button type="button" class="close custom-modal-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body custom-modal-body">
        <!-- Message will be inserted here -->
      </div>
      <div class="modal-footer custom-modal-footer">
        <button type="button" class="btn btn-secondary custom-modal-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>