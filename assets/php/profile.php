<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../src/login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, gender=?, dob=?, age=?, height_cm=?, weight_kg=?, phone=?, profile_complete=1 WHERE id=?");
    $stmt->bind_param("ssssiidsi", $firstName, $lastName, $gender, $dob, $age, $height, $weight, $phone, $userId);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error saving profile.";
    }
}
?>

<!-- not done with this code yet  BUT it is connected to the database-->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/profile.css">
</head>


<body>

    <div id="header">
        <a href="welcome.html">
            <div class="logo">
                <img src="../images/icons/mayo-logo.svg" alt="Mayo_Logo">
            </div>
        </a>
    </div>




    <div id="container">

        <h2 class="welcome">Welcome <?php echo ucfirst(htmlspecialchars($_SESSION['username'])); ?>!</h2>



        <form method="POST" action="">
            <label for="name">First Name</label>

            <input type="text" id="name" name="firstName" placeholder="Enter your first name">

            <label for="name">Last Name</label>
            
            <input type="text" id="name" name="lastName" placeholder="Enter your last name">
    
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
    
            <label for="age">Age</label>
            <input type="number" id="age" name="age" placeholder="Enter your age">
    
            <label for="height">Height (cm)</label>
            <input type="number" id="height" name="height" placeholder="Enter your height">
    
            <label for="weight">Weight (kg)</label>
            <input type="number" id="weight" name="weight" placeholder="Enter your weight">
    
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="e.g. 123-456-7890">
    
            <button class="submit-btn" type="submit">Save Profile</button>
        </form>

    </div>

    
    <script>
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
    </script>


    <footer class="footer">
        <p>&copy; 2025 <span>CS268 Group 5</span>. All rights reserved.</p>
    </footer>
        
    
</body>
</html>