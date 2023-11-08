<?php
// Include necessary files
require_once 'admin_functions.php';

// Check if the current user is an admin
if (!currentUserIsAdmin()) {
    header('Location: unauthorized_access.php'); // Redirect to an error page or login page
    exit();
}
// Fetch statistics for the dashboard
$totalUsersResult = executeQuery("SELECT COUNT(*) AS total FROM users");
$totalUsers = $totalUsersResult[0]['total'] ?? 'N/A'; // Use null coalescing operator to handle undefined index

$activeUsersResult = executeQuery("SELECT COUNT(*) AS active FROM users WHERE last_login > DATE_SUB(NOW(), INTERVAL 1 WEEK)");
$activeUsers = $activeUsersResult[0]['active'] ?? 'N/A'; // Use null coalescing operator to handle undefined index

$messagesQuery = "
    SELECT 
        COUNT(*) as sent_count, 
        (SELECT COUNT(*) FROM chat_history WHERE response IS NOT NULL) as received_count
    FROM chat_history";
$messagesResult = executeQuery($messagesQuery);
$messagesSent = $messagesResult[0]['sent_count'] ?? 'N/A'; // Use null coalescing operator to handle undefined index
$messagesReceived = $messagesResult[0]['received_count'] ?? 'N/A'; // Use null coalescing operator to handle undefined index

$activeSubscriptionsResult = executeQuery("SELECT COUNT(*) AS subscriptions FROM subscriptions WHERE status = 'active'");
$activeSubscriptions = $activeSubscriptionsResult[0]['subscriptions'] ?? 'N/A'; // Use null coalescing operator to handle undefined index

$systemHealthRecords = getSystemHealth();

// Render the page
include 'admin_header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dating.AI - Admin Dashboard</title>

<?php include "admin_head.php"; ?> <!-- Include the styling/scripts -->

</head>
<body>
<div class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <div class="statistics">
        <div class="stat">
            <h2>Total Users</h2>
            <p><?php echo $totalUsers; ?></p>
        </div>
        <div class="stat">
            <h2>Active Users</h2>
            <p><?php echo $activeUsers; ?></p>
        </div>
        <div class="stat">
            <h2>Messages Sent</h2>
            <p><?php echo $messagesSent; ?></p>
        </div>
        <div class="stat">
            <h2>Messages Received</h2>
            <p><?php echo $messagesReceived; ?></p>
        </div>
        <div class="stat">
            <h2>Active Subscriptions</h2>
            <p><?php echo $activeSubscriptions; ?></p>
        </div>
        <div class="stat">
            <h2>System Health</h2>
            <?php if (!empty($systemHealthRecords)): ?>
                <ul>
                    <?php foreach ($systemHealthRecords as $record): ?>
                        <li><?php echo htmlspecialchars($record['metric_name']) . ': ' . htmlspecialchars($record['metric_value']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No system health records found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>

