<?php
session_start();
include 'db.php';

// check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "Please log in to use the mood tracker.";
    exit;
}

$username = $_SESSION['username'];
$userId = null;

// get the user's ID from the database
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

// check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mood'], $_POST['rating'], $_POST['reason'])) {
    $mood = $_POST['mood'];
    $rating = $_POST['rating'];
    $reason = $_POST['reason'];

    // save mood entry
    if ($mood != "" && $rating != "" && $reason != "" && $userId != null) {
        $sql = "INSERT INTO mood_entries (user_id, mood, rating, reason) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isis", $userId, $mood, $rating, $reason);
        $stmt->execute();
        $stmt->close();
    }
}

// get mood history for user
$moodHistory = [];

if ($userId != null) {
    $sql = "SELECT mood, rating, reason, entry_date FROM mood_entries WHERE user_id = ? ORDER BY entry_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $moodHistory[] = $row;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mood Tracker</title>
    <link rel="stylesheet" href="../css/mood_tracker.css">
</head>
<body>

    <!-- the header  -->
    <div id="header">

        <div class="left_part">

            

            <a href="../../src/welcome.html">
                <div class="logo">
                    <img src="../images/icons/mayo-logo.svg" alt="Mayo_Logo">
                </div>
            </a>
            
            <div class="nav_toggle" onclick="toggleMenu()">☰</div>
            <div class="main_menu">
                <a href="../../assets/php/Dashboard.php" class="nav_box">Dashboard</a>
                <a href="../../assets/php/weight-tracker.php" class="nav_box">Weight Tracker</a>
                <a href="../../src/bmi.html" class="nav_box">BMI Calculator</a>
                <a href="../../src/meet_the_team.html" class="nav_box">Meet The Team</a>
                <a href="../../assets/php/mood-tracker.php" class="nav_box">Mood Tracker</a>
                <a href="../../src/FAQ.html" class="nav_box">Q & A</a>
            </div>
            
            <div class="dropdown_menu" id="dropdownMenu">
                <a href="../../assets/php/Dashboard.php" class="nav_box">Dashboard</a>

                <a href="../../assets/php/weight-tracker.php" class="nav_box">Weight Tracker</a>
                <a href="../../src/bmi.html" class="nav_box">BMI Calculator</a>
                <a href="../../src/meet_the_team.html" class="nav_box">Meet The Team</a>
                <a href="../../assets/php/mood-tracker.php" class="nav_box">Mood Tracker</a>                
                <a href="../../src/FAQ.html" class="nav_box">Q & A</a>

            </div>

            

        </div>
        

        <!-- the request appointment adn login button -->
        <div class="right_part">            

            <div class="right_box">
                <a href="appointment.html">Request appointment</a> 
            </div>
            <div class="right_box">
                <!-- <a href="../src/login.html">Log in</a> -->
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

<div id="input_part">
    <h1>How are you feeling today?</h1>

    <form method="POST" action="mood-tracker.php">
        <input type="hidden" name="mood" id="moodInput">
        
        <div>
            <button type="button" class="happy" onclick="setMood('Happy')">Happy</button>
            <button type="button" class="sad" onclick="setMood('Sad')">Sad</button>
            <button type="button" class="angry" onclick="setMood('Angry')">Angry</button>
            <button type="button" class="neutral" onclick="setMood('Neutral')">Neutral</button>
            <button type="button" class="excited" onclick="setMood('Excited')">Excited</button>
            <button type="button" class="nervous" onclick="setMood('Nervous')">Nervous</button>
        </div>

        <div class="rating" id="rating-div" style="display: none;">
            <label for="rating">Rate your mood (1-10): </label>
            <input type="number" id="rating" name="rating" min="1" max="10" step="1">
            <button type="button" onclick="nextStep()">Next</button>
        </div>

        <div class="reason" id="reason-div" style="display: none;">
            <label for="reason">Why do you feel that way?</label>
            <textarea name="reason" id="reason-text" placeholder="Enter your reason here..." rows="4" cols="50" required></textarea>
            <br>
            <button type="submit">Submit Mood</button>
        </div>
    </form>

    <h2>Your Mood History</h2>
    <ul id="mood-history-list">
        <?php foreach ($moodHistory as $entry): ?>
            <li>
                <?php echo htmlspecialchars($entry['mood']) . " (Rating: " . $entry['rating'] . ") - " . 
                     htmlspecialchars($entry['reason']) . " - " . $entry['entry_date']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    function setMood(mood) {
        document.getElementById('moodInput').value = mood;
        document.getElementById('rating-div').style.display = 'block';
    }

    function nextStep() {
        const rating = document.getElementById('rating').value;
        if (rating < 1 || rating > 10 || rating === "") {
            alert("Please enter a valid rating between 1 and 10.");
        } else {
            document.getElementById('rating-div').style.display = 'none';
            document.getElementById('reason-div').style.display = 'block';
        }
    }
</script>

<footer class="footer">
    <p>&copy; 2025 <span>CS268 Group 5</span>. All rights reserved.</p>
</footer>

</body>
</html>
