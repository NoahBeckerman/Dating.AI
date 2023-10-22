$(document).ready(function() {
    // Show the modal if there is an error or response
    if ($('#responseModalBody').html().trim() !== '') {
      $('#responseModal').modal('show');
    }
  });