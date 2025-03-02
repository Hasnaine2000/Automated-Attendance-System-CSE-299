<?php
$host = "localhost"; // Change if necessary
$user = "root"; // Change to your DB username
$pass = ""; // Change to your DB password
$db = "attendancesystem"; // Your database name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
