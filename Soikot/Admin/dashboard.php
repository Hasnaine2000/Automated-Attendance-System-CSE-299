<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-900 text-white flex flex-col items-center min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white w-full shadow-md">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Left: Logo & Title -->
            <div class="flex items-center">
                <img src="logo.png" alt="Logo" class="h-12 mr-3">
                <span class="text-2xl font-bold text-blue-900">Automated Attendance System</span>
            </div>
            
            <!-- Right: Logout Button -->
            <a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-md">Logout</a>
        </div>
    </nav>

    <div class="flex flex-col items-center justify-center flex-grow w-full">
        <!-- Options Container -->
        <div class="bg-white bg-opacity-90 text-gray-800 p-6 rounded-lg shadow-md w-full max-w-2xl text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Welcome Admin</h2>
            
            <!-- Options Grid -->
            <div class="grid grid-cols-2 gap-6">
                <a href="add_students/index.html" class="block">
                    <div class="p-4 bg-white rounded-md shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <div class="bg-blue-500 h-24 rounded-t-md"></div>
                        <div class="p-4">
                            <h2 class="text-blue-600 font-bold">Add Students</h2>
                            <p class="text-gray-500">Register new students</p>
                        </div>
                    </div>
                </a>

                <a href="initialize_classroom/classroom_initializer.php" class="block">
                    <div class="p-4 bg-white rounded-md shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <div class="bg-blue-500 h-24 rounded-t-md"></div>
                        <div class="p-4">
                            <h2 class="text-blue-600 font-bold">Classroom Initializer</h2>
                            <p class="text-gray-500">Set up courses and sections</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

</body>
</html>
