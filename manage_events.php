<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

$dbc = new mysqli("localhost", "csc350", "xampp", "event_management");
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Handle form submission for adding/updating events
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $description = $_POST['event_description'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];  
    $location = $_POST['location'];
    $club_id = $_POST['club_id'];
    
    $stmt = $dbc->prepare("INSERT INTO events (club_id, event_name, event_description, event_date, event_time, location) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $club_id, $event_name, $description, $date, $time, $location);
    $stmt->execute();
    $stmt->close();

    // Set success message in session
    $_SESSION['message'] = "Event added successfully!";
}

// Fetch all events with club name
$events = $dbc->query("SELECT e.event_id, e.event_name, e.event_description, e.event_date, e.event_time, e.location, c.club_name 
                       FROM events e
                       JOIN clubs c ON e.club_id = c.club_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Events</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="manage_events.css">
</head>
<body>
    <header><h1>Manage Events</h1></header>
    <div class="button-container">
        <a href="events.php" class="button-link">Events page</a>
    </div>
    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message"><?= $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); // Clear the message after it's displayed ?>
        <?php endif; ?>

       
        <form method="post" class="event-form">
            <label>Club:</label>
            <select name="club_id" required>
                <?php 
                    $result = $dbc->query("SELECT * FROM clubs");
                    while($club = $result->fetch_assoc()): 
                ?>
                    <option value="<?= $club['club_id'] ?>"><?= htmlspecialchars($club['club_name']) ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Event Name:</label>
            <input type="text" name="event_name" required>

            <label>Description:</label>
            <textarea name="event_description" required></textarea>

            <label>Date:</label>
            <input type="date" name="event_date" required>

            <label>Time:</label>
            <input type="time" name="event_time" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <button type="submit">Add Event</button>
        </form>


        <h2>Existing Events</h2>
        <table>
            <thead>
                <tr>
                    <th>Club Name</th>
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($event = $events->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['club_name']) ?></td> 
                        <td><?= htmlspecialchars($event['event_name']) ?></td>
                        <td><?= htmlspecialchars($event['event_description']) ?></td>
                        <td><?= htmlspecialchars($event['event_date']) ?></td>
                        <td><?= date("g:i A", strtotime($event['event_time'])) ?></td> 
                        <td><?= htmlspecialchars($event['location']) ?></td>
                        <td>
                            <a href="edit_event.php?id=<?= $event['event_id'] ?>">Edit</a> 
                            <a href="delete_event.php?id=<?= $event['event_id'] ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>

<?php
$dbc->close();
?>
