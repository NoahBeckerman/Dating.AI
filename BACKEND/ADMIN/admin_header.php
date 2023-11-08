<?php if (currentUserIsAdmin()) { ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Admin Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbarNav" aria-controls="adminNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="adminNavbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="admin_dashboard.php">Overview</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_users.php">User Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_messages.php">Messages</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_plans.php">Plan Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_billing.php">Billing History</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_cost_estimator.php">Cost Estimation</a>
      </li>
      <!-- Add additional admin links as needed -->
      <li class="nav-item">
        <a class="nav-link" href="signout.php">Sign Out</a>
      </li>
    </ul>
  </div>
</nav>

<?php
} else
 {  redirect('../../index.php'); 
}; 
?>