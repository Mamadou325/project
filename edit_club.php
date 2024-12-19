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

// Validate and get club ID from the URL
$club_id = $_GET['id'] ?? null;
if (!$club_id) {
    $_SESSION['message'] = "Invalid club ID.";
    header('Location: manage_clubs.php');
    exit();
}

// Handle form submission for updating the club
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $club_name = $_POST['club_name'];
    $description = $_POST['description'];

    $stmt = $dbc->prepare("UPDATE clubs SET club_name = ?, description = ? WHERE club_id = ?");
    $stmt->bind_param("ssi", $club_name, $description, $club_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Club updated successfully!";
    header('Location: manage_clubs.php');
    exit();
}

// Fetch the club's existing details
$stmt = $dbc->prepare("SELECT club_name, description FROM clubs WHERE club_id = ?");
$stmt->bind_param("i", $club_id);
$stmt->execute();
$result = $stmt->get_result();
$club = $result->fetch_assoc();
$stmt->close();

if (!$club) {
    $_SESSION['message'] = "Club not found.";
    header('Location: manage_clubs.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Club</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="edit_club.css">
</head>
<body>
    <header><h1>Edit Club</h1></header>
    <div class="button-container">
        <a href="manage_clubs.php" class="button-link">Back to Manage Clubs</a>
    </div>
    <main>
        <form method="post" class="club-form">
            <label>Club Name:</label>
            <input type="text" name="club_name" value="<?= htmlspecialchars($club['club_name']) ?>" required>

            <label>Description:</label>
            <textarea name="description" required><?= htmlspecialchars($club['description']) ?></textarea>

            <button type="submit">Update Club</button>
        </form>
    </main>
</body>
</html>

<?php
$dbc->close();
?>
