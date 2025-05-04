<?php
session_start();
include 'db.php';

$first_name = htmlspecialchars($_SESSION['first_name'] ?? 'Guest');
$username = $_SESSION['username'] ?? null;

if (!$username) {
    header("Location: ../../src/login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $height = (int)$_POST['height'];
    $weight = (int)$_POST['weight'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users 
            SET gender = ?, dob = ?, height = ?, weight = ?, phone = ? 
            WHERE username = ? 
            ORDER BY id DESC 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiis", $gender, $dob, $height, $weight, $phone, $username);

    if ($stmt->execute()) {
        if ($stmt->affected_rows === 0) {
            echo "No rows updated — check if the username exists.";
        } else {
            header("Location: dashboard.php");
            exit;
        }
    } else {
        die("Error: " . $stmt->error);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>

    <link rel="stylesheet" href="../../assets/css/profile.css">
</head>


<body>

    <div id="header">
        <a href="../../src/welcome.html">
            <div class="logo">
                <img src="../../assets/images/icons/mayo-logo.svg" alt="Mayo_Logo">
            </div>
        </a>
    </div>




    <div id="container">

        <h2 class="welcome">Welcome <?php echo $first_name; ?>!</h2>


        <form method="POST" action="">       

            <label for="gender">Gender</label>


            <select id="gender" name="gender">
                <option value="">Select gender</option>
                <option value="female">Female</option>
                <option value="male">Male</option>
                <option value="other">Other</option>
                <option value="prefer_not_say">Prefer not to say</option>
            </select>

    
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob">

            <label for="height">Height (cm)</label>
            <input type="number" id="height" name="height" placeholder="Enter your height">
    
            <label for="weight">Weight (kg)</label>
            <input type="number" id="weight" name="weight" placeholder="Enter your weight">
    
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="e.g. 123-456-7890">
    
            <button class="submit-btn" type="submit">Save Profile</button>
        </form>

    </div>

    
    <!-- <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); 
    

            const profileData = {
                firstName: document.querySelector('input[name="firstName"]').value,
                lastName: document.querySelector('input[name="lastName"]').value,
                gender: document.querySelector('#gender').value,
                dob: document.querySelector('#dob').value,
                age: document.querySelector('#age').value,
                height: document.querySelector('#height').value,
                weight: document.querySelector('#weight').value,
                phone: document.querySelector('#phone').value
            };
    

            localStorage.setItem('profileData', JSON.stringify(profileData));
    

            alert('Your profile information has been saved successfully.');
        });
    </script> -->


    <footer class="footer">
        <p>&copy; 2025 <span>CS268 Group 5</span>. All rights reserved.</p>
    </footer>
        
    
</body>
</html>