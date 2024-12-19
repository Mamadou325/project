<?php
session_start(); // Start the session for login tracking

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded admin credentials for demonstration (replace with a database query in production)
    $admin_user = 'admin';
    $admin_pass = 'password123';

    // Validate credentials
    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['loggedin'] = true;
        header("Location: admin_panel.php");  // Redirect to the admin panel
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <h1>Admin Login</h1>
    </header>
    <main>
        <form action="login.php" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
            <?php if (!empty($error)) {print "<p class='error'>$error</p>";} ?>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 University Clubs Event Management System</p>
    </footer>
</body>
</html>
