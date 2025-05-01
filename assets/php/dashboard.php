<?php
session_start(); // Start the session
include 'db.php'; // Connect to the database

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../src/login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

    <!-- header -->
    <div id="header">
        <div class="left_part">
            <a href="../../src/welcome.html">
                <div class="logo">
                    <img src="../images/icons/mayo-logo.svg" alt="Mayo_Logo">
                </div>
            </a>

            <div class="nav_toggle" onclick="toggleMenu()">☰</div>

            <div class="main_menu">
                <a href="dashboard.php" class="nav_box">Dashboard</a>
                <a href="weight-tracker.php" class="nav_box">Weight Tracker</a>
                <a href="../../src/bmi.html" class="nav_box">BMI Calculator</a>
                <a href="../../src/meet_the_team.html" class="nav_box">Meet The Team</a>
                <a href="../../src/mood_tracker.html" class="nav_box">Mood Tracker</a>
                <a href="../../src/social-support.html" class="nav_box">Social Support</a>
                <a href="../../src/FAQ.html" class="nav_box">Q & A</a>
            </div>
        </div>

        <!-- right side of header -->
        <div class="right_part">
            <div class="right_box">
                <span class="user_label"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
            <div class="right_box">
                <form action="logout.php" method="post" style="display: inline;">
                    <button type="submit">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButton = document.querySelector('.nav_toggle');
            const menu = document.getElementById('dropdownMenu');
            toggleButton.addEventListener('click', function () {
                menu.classList.toggle('open');
            });
        });
    </script>

    <!-- welcome header -->
    <div id="welcome_header">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    </div>
<!-- still need to add the rest of the code for the dashboard page for analytics -->
    <div id="welcome_header2">
        <h2>How are you feeling today?</h2>
    <!-- main content -->
    <div id="container">
        <div id="info_box1"> <!-- user info --> </div>
        <div id="info_box2"> <!-- weight tracker --> </div>
        <div id="info_box3"> <!-- mood tracker --> </div>
        <div id="info_box4"> <!-- appointment history --> </div>
    </div>
    <div class="footer">
        <p>&copy; 2025 <span>CS268 Group 5</span>. All rights reserved.</p>
    </div>
</body>
</html>
