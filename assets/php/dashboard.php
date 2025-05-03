<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../src/login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user profile info
$user_sql = $conn->prepare("SELECT username, email, first_name, last_name, gender, dob, height, weight, phone FROM users WHERE id = ?");
$user_sql->bind_param("i", $user_id);
$user_sql->execute();
$user_result = $user_sql->get_result();
$user = $user_result->fetch_assoc();
$user_sql->close();

// Get last 5 weight entries
$weight_sql = $conn->prepare("SELECT entry_date, weight FROM user_weights WHERE user_id = ? ORDER BY entry_date DESC LIMIT 5");
$weight_sql->bind_param("i", $user_id);
$weight_sql->execute();
$weight_result = $weight_sql->get_result();
$weights = $weight_result->fetch_all(MYSQLI_ASSOC);
$weight_sql->close();

// Get last 5 mood entries
$mood_sql = $conn->prepare("SELECT mood, rating, reason, entry_date FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC LIMIT 5");
$mood_sql->bind_param("i", $user_id);
$mood_sql->execute();
$mood_result = $mood_sql->get_result();
$moods = $mood_result->fetch_all(MYSQLI_ASSOC);
$mood_sql->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

    <!-- the header  -->
    <div id="header">

        <div class="left_part">

            

            <a href="welcome.html">
                <div class="logo">
                    <img src="../images/icons/mayo-logo.svg" alt="Mayo_Logo">
                </div>
            </a>
            
            <div class="nav_toggle" onclick="toggleMenu()">☰</div>

            <div class="main_menu">
                <a href="weight-tracker.html" class="nav_box">Weight Tracker</a>
                <a href="bmi.html" class="nav_box">BMI Calculator</a>
                <a href="meet_the_team.html" class="nav_box">Meet The Team</a>
                <a href="mood_tracker.html" class="nav_box">Mood Tracker</a>
                <a href="social-support.html" class="nav_box">Social Support</a>
                <a href="q-and-a.html" class="nav_box">Q & A</a>
            </div>
            
            <div class="dropdown_menu" id="dropdownMenu">
                <a href="weight-tracker.html" class="nav_box">Weight Tracker</a>
                <a href="bmi.html" class="nav_box">BMI Calculator</a>
                <a href="meet_the_team.html" class="nav_box">Meet The Team</a>
                <a href="mood_tracker.html" class="nav_box">Mood Tracker</a>
                <a href="social-support.html" class="nav_box">Social Support</a>
                <a href="q-and-a.html" class="nav_box">Q & A</a>
            </div>

            

        </div>
        

        <!-- the request appointment adn login button -->
        <div class="right_part">            

            <div class="right_box">
                <a href="appointment.html">Request appointment</a> 
            </div>
            <div class="right_box">
                <a href="../src/login.html">Log in</a>
            </div>
                <!-- logout button -->
    <form action="logout.php" method="post">
        <button type="submit">Log Out</button>
    </form>
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


    <div id="container">
    <div id="info_box1">
    <h2>User Information</h2>
    <p>Name: <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Phone: <?= htmlspecialchars($user['phone']) ?></p>
    <p>Gender: <?= htmlspecialchars($user['gender']) ?></p>
    <p>DOB: <?= htmlspecialchars($user['dob']) ?></p>
    <p>Height: <?= htmlspecialchars($user['height']) ?> cm</p>
    <p>Weight: <?= htmlspecialchars($user['weight']) ?> kg</p>
</div>

<div id="info_box2">
    <h2>Recent Weight Entries</h2>
    <ul>
        <?php foreach ($weights as $w): ?>
            <li><?= $w['entry_date'] ?> - <?= $w['weight'] ?> kg</li>
        <?php endforeach; ?>
    </ul>
</div>

<div id="info_box3">
    <h2>Recent Mood Entries</h2>
    <ul>
        <?php foreach ($moods as $m): ?>
            <li><?= $m['entry_date'] ?> - <?= htmlspecialchars($m['mood']) ?> (<?= $m['rating'] ?>/10): <?= htmlspecialchars($m['reason']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div id="info_box4">
    <h2>Appointment History</h2>
    <ul>
        <li>April 12, 2025 – Nutrition Check-In (Completed)</li>
        <li>March 30, 2025 – Post-Surgery Follow-Up (Completed)</li>
        <li>March 15, 2025 – Mental Health Check (Missed)</li>
        <li>February 20, 2025 – Monthly Progress Review (Completed)</li>
        <li>January 18, 2025 – Initial Consultation (Completed)</li>
    </ul>
</div>








</body>
</html>
