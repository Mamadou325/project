<?php 
// Database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "csc350";
$password = "xampp";
$database = "event_management";

$conn = new mysqli($servername, $username, $password, $database);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and validate form data
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : null;
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : null;
    $first_name = isset($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : null;
    $last_name = isset($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : null;
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : null;
    $rsvp_status = isset($_POST['rsvp_status']) ? htmlspecialchars(trim($_POST['rsvp_status'])) : null;

    // Check for missing fields
    if (empty($event_id) || empty($student_id) || empty($first_name) || empty($last_name) || empty($email) || empty($rsvp_status)) {
        die("All fields are required. Please fill out the form completely.");
    }    

    // Insert data into rsvps table
    $sql = "INSERT INTO rsvps (event_id, student_id, first_name, last_name, email, rsvp_status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("iissss", $event_id, $student_id, $first_name, $last_name, $email, $rsvp_status);

    if ($stmt->execute()) {
        echo "RSVP submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request. Please submit the form.";
}

$conn->close();
?>