<?php
$dbc = new mysqli("localhost", "csc350", "xampp", "event_management");

if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$sql_announcements = "SELECT a.announcement_text, a.announcement_date, c.club_name FROM announcements AS a INNER JOIN clubs AS c ON c.club_id = a.club_id ORDER BY a.announcement_date DESC";
$result_announcements = $dbc->query($sql_announcements);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Announcements</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="announcements.css"> 
</head>
<body>
    <header>
        <h1>Announcements</h1>
    </header> 

    <section>
        <div class="events-list">
            <div class="back-button">
                <a href="main_page.php" class="button-link">Home Page</a>
            </div>

            <?php
            if ($result_announcements->num_rows > 0) {
                // Output data of each announcement
                while ($row = $result_announcements->fetch_assoc()) {
                    print "<div class='announcement'>";
                    print "<p class='club-name'><strong>Club:</strong> " . htmlspecialchars($row['club_name']) . "</p>";
                    print "<p><strong>Announcement:</strong> " . htmlspecialchars($row['announcement_text']) . "</p>";
                    print "<p><strong>Date:</strong> " . date("F j, Y", strtotime($row['announcement_date'])) . "</p>";

                    print "</div>";
                }
            } else {
                print "<p>No announcements found</p>";
            }
            ?>

        </div>
    </section>

    <?php
    $dbc->close();
    ?>

</body>
</html>
