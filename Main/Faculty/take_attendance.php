<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION["fid"])) {
    header("Location: faculty_login.php");
    exit();
}

if (!isset($_POST["course_id"]) || !isset($_POST["section_id"])) {
    echo "Invalid access!";
    exit();
}

$course_id = escapeshellarg($_POST["course_id"]);
$section_id = escapeshellarg($_POST["section_id"]);

// Execute the Python script
$output = shell_exec("python take_attendance.py $course_id $section_id 2>&1");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taking Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-900 to-blue-800 text-white flex flex-col items-center min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white w-full shadow-md py-4">
        <div class="container mx-auto flex items-center justify-between px-6">
            <div class="flex items-center">
                <img src="logo.png" alt="Logo" class="h-12 mr-3">
                <span class="text-2xl font-bold text-blue-900">Automated Attendance System</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex flex-1 items-center justify-center mt-12">
        <div class="bg-white text-gray-800 p-10 rounded-xl shadow-lg w-[500px] text-center"> <!-- Made the box wider -->
            <h2 class="text-3xl font-extrabold text-blue-600 mb-3">Attendance Result!</h2>
            <div class="border-t-4 border-blue-500 w-20 mx-auto mb-6"></div>

            <!-- Output from the Python script -->
            <pre class="text-left bg-gray-100 p-4 rounded-lg overflow-auto"><?php echo htmlspecialchars($output); ?></pre>

            <!-- Back Button with corrected link -->
            <a href="faculty_dashboard.php">
                <button class="bg-gray-600 text-white py-3 rounded-lg w-full font-semibold transition duration-300 hover:bg-gray-700 hover:shadow-xl mt-4">Finish Class</button>
            </a>

        </div>
    </div>

</body>

</html>