<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $id = intval($_POST['id']); // Ensure it's an integer
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM admin WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if ($password === $db_password) {
            $_SESSION['admin_id'] = $id;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location='admin_login.php';</script>";
        }
    } else {
        echo "<script>alert('Admin ID not found'); window.location='admin_login.php';</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-blue-900 text-white flex flex-col items-center min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white w-full shadow-md">
        <div class="container mx-auto px-6 py-4 flex items-center">
            <img src="logo.png" alt="Logo" class="h-12 mr-3">
            <span class="text-2xl font-bold text-blue-900">Automated Attendance System</span>
        </div>
    </nav>

    <!-- Centering the Login Form -->
    <div class="flex flex-1 items-center justify-center mt-12">
        <div class="bg-white text-gray-800 p-8 rounded-lg shadow-lg w-96">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-blue-500 mb-4">Admin Login</h2>
                <div class="border-t-2 border-blue-500 w-16 mx-auto mb-6"></div>
            </div>

            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-600 font-semibold">Admin ID</label>
                    <input type="text" name="id" placeholder="Enter Admin ID" required
                        class="border p-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-semibold">Password</label>
                    <input type="password" name="password" placeholder="Enter Password" required
                        class="border p-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit" name="login" class="bg-blue-500 text-white p-2 rounded w-full font-semibold hover:bg-blue-600 transition">Login</button>
            </form>

            <div class="text-center mt-4">
                <a href="#" class="text-blue-500 hover:underline">Forgot Password?</a>
            </div>
        </div>
    </div>

</body>
</html>
