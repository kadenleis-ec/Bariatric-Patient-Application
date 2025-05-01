<?php
session_start(); // start the session so we can clear it

session_unset(); // remove all session variables
session_destroy(); // end the session completely

// send user back to the login page
header("Location: ../../src/login.html");
exit;
?>
