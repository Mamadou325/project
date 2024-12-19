<?php
// Connect to database 
$dbc = new mysqli("localhost", "csc350", "xampp", "event_management");

// Check connection
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Fetch events from the database    <a href="main_page.php" class="button">Home Page</a>
$sql = "SELECT c.club_name, e.event_name, e.event_description, e.event_date, e.event_time, e.location FROM events AS e  INNER JOIN clubs AS c ON c.club_id = e.club_id";
$result = $dbc->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Events Page</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="events.css">
</head>
<body>
    <header>
        <h1>Events for Clubs</h1>
    </header> 

    <section>
        <h2>View the exciting events on campus!</h2>
        <div class="events-list">
        <div class="back-button">
        <a href="main_page.php" class="button-link">Home Page</a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="rsvp_form.php" class="button-link">RSVP</a>
        </div>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    print "<div class='event'>";
                    print "<p class='club-name'><strong>Club:</strong> " . htmlspecialchars($row['club_name']) . "</p>";
                    print "<h3>" . htmlspecialchars($row['event_name']) . "</h3>";
                    print "<p><strong>Description:</strong> " . htmlspecialchars($row['event_description']) . "</p>";
                    print "<p><strong>Date:</strong> " . htmlspecialchars($row['event_date']) . "</p>";
                    print "<p><strong>Time:</strong> " . date("g:i A", strtotime($row['event_time'])) . "</p>";
                    print "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                    print "</div>";
                }
            } else {
                print "<p>No events found</p>";
            }
            ?>
        </div>
    </section>

    <?php
    $dbc->close();
    ?>
    
</body>
</html>
