<?php
session_start(); // Start the session at the top
include 'db.php'; // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];

    if ($email !== $confirm_email) {
        echo "Emails do not match.";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $first_name, $last_name);

    if ($stmt->execute()) {
        $_SESSION['first_name'] = $first_name; 
        $_SESSION['username'] = $username;
        header("Location: profile.php"); 
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
