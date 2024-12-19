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

// Handle form submission for adding/updating clubs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $club_name = $_POST['club_name'];
    $description = $_POST['description'];
    
   
    if (isset($_POST['club_id']) && !empty($_POST['club_id'])) {
        $club_id = $_POST['club_id'];
        $stmt = $dbc->prepare("UPDATE clubs SET club_name = ?, description = ? WHERE club_id = ?");
        $stmt->bind_param("ssi", $club_name, $description, $club_id);
        $stmt->execute();
        $_SESSION['message'] = "Club updated successfully!";
    } else {
        $stmt = $dbc->prepare("INSERT INTO clubs (club_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $club_name, $description);
        $stmt->execute();
        $_SESSION['message'] = "Club added successfully!";
    }
    $stmt->close();
}

// Fetch all clubs
$clubs = $dbc->query("SELECT * FROM clubs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Clubs</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="manage_clubs.css">
</head>
<body>
    <header><h1>Manage Clubs</h1></header>
    <div class="button-container">
        <a href="clubspage.php" class="button-link">Clubs Page</a>
    </div>
    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message"><?= $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

       
        <form method="post" class="club-form">
            <label>Club Name:</label>
            <input type="text" name="club_name" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <button type="submit">Add Club</button>
        </form>

       
        <h2>Existing Clubs</h2>
        <table>
            <thead>
                <tr>
                    <th>Club Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($club = $clubs->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($club['club_name']) ?></td>
                        <td><?= htmlspecialchars($club['description']) ?></td>
                        <td>
                            <a href="edit_club.php?id=<?= $club['club_id'] ?>">Edit</a>
                            <a href="delete_club.php?id=<?= $club['club_id'] ?>" onclick="return confirm('Are you sure you want to delete this club?');">Delete</a>
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
