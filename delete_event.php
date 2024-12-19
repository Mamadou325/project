<?php
session_start();

// Database connection
$dbc = new mysqli("localhost", "csc350", "xampp", "event_management");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Check if event ID is set
if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);

    // Prepare and execute delete statement
    $stmt = $dbc->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Event deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting event.";
    }
    
    $stmt->close();
}

// Redirect back to manage events page
header("Location: manage_events.php");
exit();

$dbc->close();
?>
