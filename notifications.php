<?php

$servername = "localhost";
$username = "root";
$password = "csc350";
$dbname = "events_management";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT events.name AS event_name, events.date AS event_date, users.email AS user_email
        FROM events
        JOIN users ON events.user_id = users.id
        WHERE events.date >= NOW() AND events.date <= DATE_ADD(NOW(), INTERVAL 1 DAY)";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventName = $row['event_name'];
        $eventDate = $row['event_date'];
        $userEmail = $row['user_email'];

        
        $to = $userEmail;
        $subject = "Reminder: Upcoming Event - $eventName";
        $message = "Hi there,\n\nThis is a friendly reminder about your upcoming event:\n\n" .
                   "Event: $eventName\nDate: $eventDate\n\n" .
                   "Thank you,\nEvent Team";
        $headers = "From: events@example.com";

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            echo "Reminder email sent to $to for event $eventName\n";
        } else {
            echo "Failed to send email to $to\n";
        }
    }
} else {
    echo "No upcoming events to send reminders for.\n";
}

$conn->close();
?>