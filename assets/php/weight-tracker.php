<?php
session_start();
include 'db.php';

$username = $_SESSION['username'] ?? null;
$user_id = null;
$initial_weight = null;

// check if user is logged in
if (!isset($_SESSION['username'])) {
  echo "Please log in to use the weight tracker.";
  exit;
}

if ($username) {
    $stmt = $conn->prepare("SELECT id, weight FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $initial_weight);
    $stmt->fetch();
    $stmt->close();
}

// Handle form submission

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['weight'], $_POST['date'])) {
    $weight = (float) $_POST['weight'];
    $date = $_POST['date'];

    if ($user_id && $weight && $date) {
        $insert = $conn->prepare("INSERT INTO user_weights (user_id, entry_date, weight) VALUES (?, ?, ?)");
        $insert->bind_param("isd", $user_id, $date, $weight);
        $insert->execute();
        $insert->close();
    }
}

// Load weight history

$weight_data = [];

if ($user_id) {

    // Add the initial weight as the first entry

    if ($initial_weight !== null) {
        $weight_data[] = [
            'entry_date' => 'Initial',
            'weight' => $initial_weight
        ];
    }

    // Load rest of entries from user_weights
    $history = $conn->prepare("SELECT entry_date, weight FROM user_weights WHERE user_id = ? ORDER BY entry_date ASC");
    $history->bind_param("i", $user_id);
    $history->execute();
    $result = $history->get_result();

    while ($row = $result->fetch_assoc()) {
        $weight_data[] = $row;
    }
    $history->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Weight Tracker</title>
  <link rel="stylesheet" href="../css/weight-tracker.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

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

        <a href="./../assets/php/weight-tracker.php" class="nav_box">Weight Tracker</a>
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
  <div id="page_wapper">
  <div id="container">
    <h2>Weight Tracker</h2>

    <form method="POST" action="" class="input-form">
      <div class="input">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required />
      </div>

      <div class="input">
        <label for="weight">Weight (kg):</label>
        <input type="number" id="weight" name="weight" step="0.1" required />
      </div>

      <button type="submit" class="adddata_btn">Add Data</button>
    </form>

    <canvas id="weightChart"></canvas>

    <script>
      const ctx = document.getElementById('weightChart').getContext('2d');
      const data = {
        labels: [],
        datasets: [{
          label: 'Weight Over Time',
          data: [],
          backgroundColor: 'lightblue',
          fill: false,
          tension: 0.2
        }]
      };

      const config = {
        type: 'line',
        data: data,
        options: {
          responsive: true,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Date'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Weight (kg)'
              },
              suggestedMin: 30,
              suggestedMax: 150
            }
          }
        }
      };

      const weightChart = new Chart(ctx, config);

      const entries = <?php echo json_encode($weight_data); ?>;
      entries.forEach(entry => {
        data.labels.push(entry.entry_date);
        data.datasets[0].data.push(entry.weight);
      });
      weightChart.update();
    </script>
  </div>
  </div>

  <footer class="footer">
    <p>&copy; 2025 <span>CS268 Group 5</span>. All rights reserved.</p>
  </footer>
</body>
</html>
