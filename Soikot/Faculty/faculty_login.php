<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fid = $_POST["fid"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM faculty WHERE fid = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION["fid"] = $fid;
        header("Location: faculty_dashboard.php");
    } else {
        echo "<script>alert('Invalid Credentials'); window.location='faculty_login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-blue-900 text-white flex flex-col items-center min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white w-full shadow-md">
        <div class="container mx-auto px-6 py-4 flex items-center">
            <!-- Logo -->
            <img src="logo.png" alt="Logo" class="h-12 mr-3">
            <!-- Title -->
            <span class="text-2xl font-bold text-blue-900">Automated Attendance System</span>
        </div>
    </nav>

    <!-- Centering the Login Form -->
    <div class="flex flex-1 items-center justify-center mt-12">
        <div class="bg-white text-gray-800 p-8 rounded-lg shadow-lg w-96">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-blue-500 mb-4">Faculty Login</h2>
                <div class="border-t-2 border-blue-500 w-16 mx-auto mb-6"></div>
            </div>

            <form method="POST" action="faculty_login.php">
                <div class="mb-4">
                    <label class="block text-gray-600 font-semibold">Faculty ID</label>
                    <input type="text" name="fid" placeholder="Enter Faculty ID" required
                        class="border p-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 font-semibold">Password</label>
                    <input type="password" name="password" placeholder="Enter Password" required
                        class="border p-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full font-semibold hover:bg-blue-600 transition">Login</button>
            </form>

            <div class="text-center mt-4">
                <a href="#" class="text-blue-500 hover:underline">Forgot Password?</a>
            </div>
        </div>
    </div>

</body>
</html>
