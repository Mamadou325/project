<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$dbc = new mysqli("localhost", "csc350", "xampp", "event_management");
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Check if 'id' parameter is present in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $announcement_id = $_GET['id'];

    // Prepare the delete statement
    $stmt = $dbc->prepare("DELETE FROM announcements WHERE announcement_id = ?");
    $stmt->bind_param("i", $announcement_id);
    
    // Execute and check success
    if ($stmt->execute()) {
        $_SESSION['message'] = "Announcement deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting announcement. Please try again.";
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "Invalid announcement ID.";
}

// Close connection and redirect
$dbc->close();
header('Location: manage_announcements.php');
exit();
?>
