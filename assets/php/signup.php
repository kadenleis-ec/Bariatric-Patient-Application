<?php
include '../assets/php/db.php'; // connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get all the input from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];

    // make sure both emails match
    if ($email !== $confirm_email) {
        echo "Emails do not match.";
        exit;
    }

    // hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // insert the new user into the database
    $sql = "INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $first_name, $last_name);

    // if it worked, send them to the login page to login after signing up
    if ($stmt->execute()) {
        echo "Sign-up successful!";
        header("Location: ../src/login.html");
    } else {
        // show error if something went wrong
        echo "Error: " . $stmt->error;
    }
}
?>
