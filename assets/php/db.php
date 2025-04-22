<?php
// set up the connection details
$host = 'localhost';      // where the database is hosted (localhost for XAMPP)
$dbname = 'cs268';        // name of your database
$username = 'root';       // default XAMPP username
$password = '';           // default XAMPP password is empty

// create a new database connection
$conn = new mysqli($host, $username, $password, $dbname);

// check if it worked, otherwise stop and show the error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
