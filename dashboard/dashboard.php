<?php
session_start(); // start the session
include '../db.php'; // connect to the database

// if the user isn't logged in, send them back to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <!-- show the user's name from the session -->
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h2>Logged in!</h2>

    <!-- logout button -->
    <form action="logout.php" method="post">
        <button type="submit">Log Out</button>
    </form>
</body>
</html>
