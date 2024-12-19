<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
$dbc = new mysqli("localhost", "csc350", "xampp", "event_management");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: main_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <form method="post" style="float: right;">
            <button type="submit" name="logout">Logout</button>
        </form>
    </header>
    <main>
        <h2>Manage Events, Announcements, and Clubs.</h2>
        <nav>
        <ul>
    <li><a href="manage_events.php" class="large-text">Manage Events</a></li>
    <li><a href="manage_announcements.php" class="large-text">Manage Announcements</a></li>
    <li><a href="manage_clubs.php" class="large-text">Manage Clubs</a></li>
    </ul>
        </nav>
    </main>
    <footer>
        <p>&copy; 2024 University Clubs Event Management System</p>
    </footer>
</body>
</html>
<?php $dbc->close(); ?>
