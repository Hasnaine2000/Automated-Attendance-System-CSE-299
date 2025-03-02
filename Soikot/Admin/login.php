<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $id = intval($_POST['id']); // Ensure it's an integer
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM admin WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" because ID is an integer
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if ($password === $db_password) { // Direct string comparison
            $_SESSION['admin_id'] = $id;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Admin ID not found'); window.location='index.php';</script>";
    }
    $stmt->close();
}
?>
