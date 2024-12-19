<!doctype html>
<html lang="en">
<head>
    <title>Clubs Page</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="events.css">
    <style>
        h2 {
            color: #0044cc;
        }
        a {
            color: inherit;
            text-decoration: inherit;
            font-weight: bold;
            margin: 0;
            font-size: 1.5em;
            color: #0044cc;
        }
        .name {
            color: #0044cc;
        }
    </style>
</head>

<body>

<header>
    <h1>Clubs</h1>
</header>



<?php

// Connect to database 
$dbc = new mysqli("localhost", "csc350", "xampp", "event_management");

// Check connection
if ($dbc->connect_error) {
    die("Connection failed: " . $dbc->connect_error);
}

$sql = "SELECT * FROM clubs";
$clubs = mysqli_query($dbc, $sql);

if (isset($_GET['club'])) { 
    $club_id = $_GET['club'];
} else {
    $club_id = 0;
}

if ($club_id != '0') {
    $sql2 = "SELECT * FROM clubs WHERE club_id = $club_id";
    $clubInfo = mysqli_query($dbc, $sql2);

    print "<div class='event'>";

    while ($row = mysqli_fetch_array($clubInfo)) {
        print "<h1 class='name'>" . $row['club_name'] . "</h1>";
        print "<p>" . $row['description'] . "</p>";
        print "<h2>Announcements</h2>";
    }

    $sql3 = "SELECT * FROM clubs c, announcements a WHERE c.club_id = a.club_id AND c.club_id = '$club_id' ORDER BY announcement_date DESC";
    $announcements = mysqli_query($dbc, $sql3);

    if ($announcements->num_rows > 0) { 
        while ($row = mysqli_fetch_array($announcements)) {   
   
           print "<p><strong>" . $row['announcement_text'] . "</strong><br>Posted: " . $row['announcement_date'] . "</p>";
        }
    } else {
        print "No announcements posted.";
    }

    print "<br><br><a href='clubspage.php' class='button-link'>Back to clubs</a>";

    print "</div";

    } else { //if no club selected
        
        print "<div class='events-list'>";
        print "<div class='back-button'> 
               <a href='main_page.php' class='button-link'>Home Page</a>
               </div>";

        while ($row = mysqli_fetch_array($clubs)) {
            print "<div class='event'";
            print "<h3><a href='clubspage.php?club=" . $row['club_id'] . "'>" . $row['club_name'] . "</a></h3>";
            print "<p><strong>Description:</strong> " . $row['description'] . "</p>"; 
            print "</div>";
        }
        print "</div";
    }
   
?>
</body>
</html>