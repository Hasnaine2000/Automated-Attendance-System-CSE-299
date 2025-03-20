<?php
session_start();
require_once "../db_connect.php";

if (!isset($_SESSION["fid"])) {
    header("Location: faculty_login.php");
    exit();
}

if (!isset($_GET["course_id"]) || !isset($_GET["section_id"]) || !isset($_GET["date"])) {
    echo "Invalid access!";
    exit();
}

$course_id = htmlspecialchars($_GET["course_id"]);
$section_id = htmlspecialchars($_GET["section_id"]);
$date = htmlspecialchars($_GET["date"]);
$fid = $_SESSION["fid"];

// Fetch all students in the course and section
$sql_students = "SELECT s.sid, s.name FROM classroom c JOIN student s ON c.sid = s.sid WHERE c.course_id = ? AND c.section_id = ?";
$stmt = $conn->prepare($sql_students);
$stmt->bind_param("ss", $course_id, $section_id);
$stmt->execute();
$result_students = $stmt->get_result();
$students = $result_students->fetch_all(MYSQLI_ASSOC);

// Fetch present students on the given date
$sql_present = "SELECT sid FROM attendance WHERE course_id = ? AND section_id = ? AND date = ?";
$stmt = $conn->prepare($sql_present);
$stmt->bind_param("sss", $course_id, $section_id, $date);
$stmt->execute();
$result_present = $stmt->get_result();
$present_sids = array_column($result_present->fetch_all(MYSQLI_ASSOC), 'sid');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details</title>
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

    <!-- Attendance Details Container -->
    <div class="flex flex-1 items-center justify-center mt-12 px-4">
        <div class="bg-white text-gray-800 p-10 rounded-xl shadow-lg w-full max-w-5xl">
            <h2 class="text-3xl font-extrabold text-blue-600 mb-3">Attendance on <?php echo $date; ?></h2>
            <p class="text-gray-600 mb-6">Here is the attendance status for the selected date</p>
            <div class="border-t-4 border-blue-500 w-20 mx-auto mb-6"></div>
            
            <!-- Attendance Table -->
            <table class="min-w-full text-left mb-4">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-lg font-semibold text-blue-600">Student ID</th>
                        <th class="py-2 px-4 border-b text-lg font-semibold text-blue-600">Name</th>
                        <th class="py-2 px-4 border-b text-lg font-semibold text-blue-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo $student['sid']; ?></td>
                            <td class="py-2 px-4 border-b"><?php echo $student['name']; ?></td>
                            <td class="py-2 px-4 border-b 
                                <?php echo in_array($student['sid'], $present_sids) ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo in_array($student['sid'], $present_sids) ? 'Present' : 'Absent'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Back Button -->
            <a href="show_dates.php?course_id=<?php echo $course_id; ?>&section_id=<?php echo $section_id; ?>">
                <button class="bg-gray-600 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-gray-700 hover:shadow-lg mt-4">
                    Back
                </button>
            </a>
        </div>
    </div>

</body>
</html>
