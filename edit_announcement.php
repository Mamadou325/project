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

// Get the announcement ID from the URL
$announcement_id = $_GET['id'] ?? null;

if (!$announcement_id) {
    $_SESSION['message'] = "Invalid announcement ID.";
    header('Location: manage_announcements.php');
    exit();
}

// Fetch existing announcement data
$stmt = $dbc->prepare("SELECT club_id, announcement_text, announcement_date FROM announcements WHERE announcement_id = ?");
$stmt->bind_param("i", $announcement_id);
$stmt->execute();
$result = $stmt->get_result();
$announcement = $result->fetch_assoc();

if (!$announcement) {
    $_SESSION['message'] = "Announcement not found.";
    header('Location: manage_announcements.php');
    exit();
}

// Handle form submission to update the announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $club_id = $_POST['club_id'];
    $announcement_text = $_POST['announcement_text'];
    $announcement_date = $_POST['announcement_date'];

    $update_stmt = $dbc->prepare("UPDATE announcements SET club_id = ?, announcement_text = ?, announcement_date = ? WHERE announcement_id = ?");
    $update_stmt->bind_param("issi", $club_id, $announcement_text, $announcement_date, $announcement_id);
    $update_stmt->execute();
    
    // Set success message and redirect
    $_SESSION['message'] = "Announcement updated successfully!";
    header('Location: manage_announcements.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="manage_announcements.css">
</head>
<body>
    <header><h1>Edit Announcement</h1></header>
    <div class="button-container">
        <a href="manage_announcements.php" class="button-link">Back to Announcements</a>
    </div>
    <main>
        <form method="post" class="announcement-form">
            <label>Club:</label>
            <select name="club_id" required>
                <?php 
                    $clubs = $dbc->query("SELECT * FROM clubs");
                    while ($club = $clubs->fetch_assoc()): 
                        $selected = ($club['club_id'] == $announcement['club_id']) ? 'selected' : '';
                ?>
                    <option value="<?= $club['club_id'] ?>" <?= $selected ?>>
                        <?= htmlspecialchars($club['club_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Announcement Text:</label>
            <textarea name="announcement_text" required><?= htmlspecialchars($announcement['announcement_text']) ?></textarea>

            <label>Date:</label>
            <input type="date" name="announcement_date" value="<?= htmlspecialchars($announcement['announcement_date']) ?>" required>

            <button type="submit">Update Announcement</button>
        </form>
    </main>
</body>
</html>

<?php
$dbc->close();
?>
