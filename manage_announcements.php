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

// Handle form submission for adding announcements
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $club_id = $_POST['club_id'];
    $announcement_text = $_POST['announcement_text'];
    $announcement_date = $_POST['announcement_date'];

    $stmt = $dbc->prepare("INSERT INTO announcements (club_id, announcement_text, announcement_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $club_id, $announcement_text, $announcement_date);
    $stmt->execute();
    $stmt->close();

    // Set success message in session
    $_SESSION['message'] = "Announcement added successfully!";
    header('Location: manage_announcements.php'); // Prevent form resubmission
    exit();
}

// Fetch all announcements with club name
$announcements = $dbc->query("
    SELECT a.announcement_id, a.announcement_text, a.announcement_date, c.club_name 
    FROM announcements a
    JOIN clubs c ON a.club_id = c.club_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Announcements</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="manage_announcements.css">
</head>
<body>
    <header><h1>Manage Announcements</h1></header>
    <div class="button-container">
        <a href="announcements.php" class="button-link">Announcements Page</a>
    </div>
    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message"><?= $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="post" class="announcement-form">
            <label>Club:</label>
            <select name="club_id" required>
                <?php 
                    $result = $dbc->query("SELECT * FROM clubs");
                    while ($club = $result->fetch_assoc()): 
                ?>
                    <option value="<?= $club['club_id'] ?>"><?= htmlspecialchars($club['club_name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Announcement Text:</label>
            <textarea name="announcement_text" required></textarea>

            <label>Date:</label>
            <input type="date" name="announcement_date" required>

            <button type="submit">Add Announcement</button>
        </form>

        <h2>Existing Announcements</h2>
        <table>
            <thead>
                <tr>
                    <th>Club</th>
                    <th>Announcement Text</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($announcement = $announcements->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($announcement['club_name']) ?></td>
                        <td><?= htmlspecialchars($announcement['announcement_text']) ?></td>
                        <td><?= htmlspecialchars(date("Y-m-d", strtotime($announcement['announcement_date']))) ?></td>
                        <td>
                            <a href="edit_announcement.php?id=<?= $announcement['announcement_id'] ?>">Edit</a> 
                            <a href="delete_announcement.php?id=<?= $announcement['announcement_id'] ?>" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</a>
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
