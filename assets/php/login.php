<?php
session_start(); // start session so we can store user info
include '../assets/php/db.php'; // connect to database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // check if the username exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // if the user is found
    if ($user = $result->fetch_assoc()) {
        // check if the password matches
        if (password_verify($password, $user['password'])) {
            // store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // go to the dashboard
            header("Location: ../src/dashboard.php");
            exit;
        } else {
            // wrong password
            echo "Incorrect password.";
        }
    } else {
        // no user with that username
        echo "User not found.";
    }
}
?>
