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

// Initialize variables for form fields
$event_id = $event_name = $description = $date = $time = $location = $club_id = "";

// Check if editing an existing event
if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
    $stmt = $dbc->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        $event_name = $event['event_name'];
        $description = $event['event_description'];
        $date = $event['event_date'];
        $time = $event['event_time'];
        $location = $event['location'];
        $club_id = $event['club_id'];
    } else {
        $_SESSION['message'] = "Event not found!";
        header('Location: events.php');
        exit();
    }
}

// Handle form submission for updating events
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = intval($_POST['event_id']);
    $event_name = $_POST['event_name'];
    $description = $_POST['event_description'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = $_POST['location'];
    $club_id = $_POST['club_id'];

    if ($event_id) {
        // Update existing event
        $stmt = $dbc->prepare("UPDATE events SET club_id = ?, event_name = ?, event_description = ?, event_date = ?, event_time = ?, location = ? WHERE event_id = ?");
        $stmt->bind_param("isssssi", $club_id, $event_name, $description, $date, $time, $location, $event_id);
        $stmt->execute();
        $_SESSION['message'] = "Event updated successfully!";
    }
    header('Location: events.php'); // Redirect to events list
    exit();
}

// Fetch all clubs for the dropdown
$clubs_result = $dbc->query("SELECT * FROM clubs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="manage_events.css">
</head>
<body>
    <header><h1><?= $event_id ? 'Edit Event' : 'Add Event' ?></h1></header>
    <div class="button-container">
        <a href="events.php" class="button-link">Back to Events</a>
    </div>
    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message"><?= $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Form to edit existing event -->
        <form method="post" class="event-form">
            <input type="hidden" name="event_id" value="<?= $event_id ?>">

            <label>Club:</label>
            <select name="club_id" required>
                <?php while($club = $clubs_result->fetch_assoc()): ?>
                    <option value="<?= $club['club_id'] ?>" <?= ($club['club_id'] == $club_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($club['club_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Event Name:</label>
            <input type="text" name="event_name" value="<?= htmlspecialchars($event_name) ?>" required>

            <label>Description:</label>
            <textarea name="event_description" required><?= htmlspecialchars($description) ?></textarea>

            <label>Date:</label>
            <input type="date" name="event_date" value="<?= htmlspecialchars($date) ?>" required>

            <label>Time:</label>
            <input type="time" name="event_time" value="<?= htmlspecialchars($time) ?>" required>

            <label>Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($location) ?>" required>

            <button type="submit"><?= $event_id ? 'Update Event' : 'Add Event' ?></button>
        </form>
    </main>
</body>
</html>

<?php
$dbc->close();
?>
