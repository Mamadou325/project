<?php
// Database connection
$servername = "localhost"; 
$username = "csc350";      
$password = "xampp";           
$database = "event_management"; 

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events from the database
$sql = "SELECT event_id, event_name, event_date, event_time, location FROM events";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Form</title>
    <link rel="stylesheet" href="rsvpform.css">
</head>
<body>
    <h1>RSVP to an Event</h1>

    <form action="rsvp_submission.php" method="POST">
        <label for="event_id">Select Event:</label>
        <select name="event_id" id="event_id" required>
            <option value="">--Select an Event--</option>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['event_id'] . "'>" 
                         . $row['event_name'] . " - " 
                         . $row['event_date'] . " at " 
                         . $row['event_time'] . " (" 
                         . $row['location'] . ")</option>";
                }
            } else {
                echo "<option value=''>No events available</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="student_id">Student id:</label>
        <input type="text" id="student_id" name="student_id" required>
        <br><br>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>
        <br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="rsvp_status">RSVP Status:</label>
        <select name="rsvp_status" id="rsvp_status" required>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
            <option value="Maybe">Maybe</option>
        </select>
        <br><br>

        <button type="submit">Submit RSVP</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>