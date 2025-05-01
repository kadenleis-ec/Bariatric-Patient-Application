<?php
session_start();
include '../php/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../src/login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weight Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/weight-tracker.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Navbar -->
<div id="header">
    <div class="left_part">
        <a href="welcome.html">
            <div class="logo">
                <img src="../images/icons/mayo-logo.svg" alt="Mayo Logo">
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
            <a href="../../src/q-and-a.html" class="nav_box">Q & A</a>
        </div>
    </div>

    <div class="right_part">
        <div class="right_box">
            <span class="user_label"><?php echo ucfirst($_SESSION['username']); ?></span>
        </div>
        <div class="right_box">
            <form action="../php/logout.php" method="post">
                <button type="submit">Log Out</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const menuBtn = document.querySelector('.nav_toggle');
        const dropdown = document.getElementById('dropdownMenu');
        menuBtn.addEventListener('click', () => {
            dropdown.classList.toggle('open');
        });
    });
</script>

<!-- Main Content -->
<div id="container">
    <h2>Weight Tracker</h2>

    <form method="POST">
        <div class="input">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="input">
            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" name="weight" step="0.1" required>
        </div>

        <button type="submit" class="adddata_btn">Add Data</button>
    </form>

    <canvas id="weightChart"></canvas>
</div>

<footer class="footer">
    <p>&copy; 2025 <span>CS268 Group 5</span>. All rights reserved.</p>
</footer>

<?php
// Save weight entry
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['date'];
    $weight = $_POST['weight'];
    $uid = $_SESSION['user_id'];

    $insert = $conn->prepare("INSERT INTO weight_entries (user_id, entry_date, weight_kg) VALUES (?, ?, ?)");
    $insert->bind_param("isd", $uid, $date, $weight);
    $insert->execute();
    $insert->close();
}

// Get weight history
$uid = $_SESSION['user_id'];
$query = $conn->query("SELECT entry_date, weight_kg FROM weight_entries WHERE user_id = $uid ORDER BY entry_date");

$dates = [];
$weights = [];

while ($row = $query->fetch_assoc()) {
    $dates[] = $row['entry_date'];
    $weights[] = $row['weight_kg'];
}
?>

<script>
    const ctx = document.getElementById('weightChart').getContext('2d');

    const weightChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($dates); ?>,
            datasets: [{
                label: 'Weight (kg)',
                data: <?= json_encode($weights); ?>,
                backgroundColor: 'lightblue',
                borderColor: '#2962ff',
                fill: false,
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { title: { display: true, text: 'Date' }},
                y: { title: { display: true, text: 'Weight (kg)' }, suggestedMin: 30, suggestedMax: 150 }
            }
        }
    });
</script>

</body>
</html>
