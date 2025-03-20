<?php
session_start();
require_once "../db_connect.php";

if (!isset($_SESSION["fid"])) {
    header("Location: faculty_login.php");
    exit();
}

if (!isset($_GET["course_id"]) || !isset($_GET["section_id"])) {
    echo "Invalid access!";
    exit();
}

$course_id = htmlspecialchars($_GET["course_id"]);
$section_id = htmlspecialchars($_GET["section_id"]);
$fid = $_SESSION["fid"];

// Fetch unique attendance dates for the course and section
$sql_dates = "SELECT DISTINCT date FROM attendance WHERE course_id = ? AND section_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql_dates);
$stmt->bind_param("ss", $course_id, $section_id);
$stmt->execute();
$result_dates = $stmt->get_result();
$dates = $result_dates->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance History</title>
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

    <!-- Attendance History Container -->
    <div class="flex flex-1 items-center justify-center mt-12">
        <div class="bg-white text-gray-800 p-10 rounded-xl shadow-lg w-96 text-center">
            <h2 class="text-3xl font-extrabold text-blue-600 mb-3">Attendance History</h2>
            <p class="text-gray-600 mb-6">Select a date to view the attendance records</p>
            <div class="border-t-4 border-blue-500 w-20 mx-auto mb-6"></div>
            
            <?php if (count($dates) > 0): ?>
                <?php foreach ($dates as $date): ?>
                    <form action="attendance_details.php" method="GET">
                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                        <input type="hidden" name="date" value="<?php echo $date['date']; ?>">
                        <button type="submit" class="bg-blue-600 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-lg mb-4">
                            <?php echo $date['date']; ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No attendance records found.</p>
            <?php endif; ?>

            <!-- Back Button with updated functionality -->
            <a href="../faculty_course.php?course_id=<?php echo $course_id; ?>&section_id=<?php echo $section_id; ?>">
                <button class="bg-gray-600 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-gray-700 hover:shadow-lg mt-4">
                    Back
                </button>
            </a>
        </div>
    </div>

</body>
</html>
