<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION["fid"])) {
    header("Location: faculty_login.php");
    exit();
}

if (!isset($_GET["course_id"]) || !isset($_GET["section_id"])) {
    echo "Invalid access?";
    exit();
}

$course_id = htmlspecialchars($_GET["course_id"]);
$section_id = htmlspecialchars($_GET["section_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Options</title>
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

    <!-- Course Options Container -->
    <div class="flex flex-1 items-center justify-center mt-12">
        <div class="bg-white text-gray-800 p-10 rounded-xl shadow-lg w-96 text-center">
            <h2 class="text-3xl font-extrabold text-blue-600 mb-3">Course: <?php echo $course_id; ?> | Section: <?php echo $section_id; ?></h2>
            <p class="text-gray-600 mb-6">Choose an option below to proceed</p>
            <div class="border-t-4 border-blue-500 w-20 mx-auto mb-6"></div>
            
            <form action="take_attendance.php" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                <button type="submit" class="bg-blue-600 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-lg">Take Attendance</button>
            </form>
            
            <a href="Manual_Attendance/manual_attendance.php?course_id=<?php echo $course_id; ?>&section_id=<?php echo $section_id; ?>">
                <button class="bg-blue-500 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-blue-600 hover:shadow-lg mt-4">Manual Attendance</button>
            </a>
            
            <a href="Attendance_History/show_dates.php?course_id=<?php echo $course_id; ?>&section_id=<?php echo $section_id; ?>">
                <button class="bg-blue-400 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-blue-500 hover:shadow-lg mt-4">Attendance History</button>
            </a>
            
            <a href="faculty_dashboard.php">
                <button class="bg-gray-600 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-gray-700 hover:shadow-lg mt-4">Back</button>
            </a>
        </div>
    </div>

</body>
</html>
