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
$date = date("Y-m-d"); // Get the system date

// Fetch students from the classroom table
$sql = "SELECT s.sid, s.name FROM classroom c 
        JOIN student s ON c.sid = s.sid 
        WHERE c.course_id = ? AND c.section_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $course_id, $section_id);
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["present_students"])) {
        $present_students = $_POST["present_students"];

        // Insert attendance data
        $insert_sql = "INSERT INTO attendance (date, course_id, section_id, sid) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);

        foreach ($present_students as $sid) {
            $insert_stmt->bind_param("ssss", $date, $course_id, $section_id, $sid);
            $insert_stmt->execute();
        }

        // Redirect to faculty dashboard after successful attendance record
        echo "<script>
                alert('Attendance recorded successfully!');
                window.location.href = '../faculty_dashboard.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('No students were marked as present.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-900 to-blue-800 text-white flex flex-col items-center min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white w-full shadow-md py-4">
        <div class="container mx-auto flex items-center justify-between px-6">
            <div class="flex items-center">
                <img src="../logo.png" alt="Logo" class="h-12 mr-3">
                <span class="text-2xl font-bold text-blue-900">Automated Attendance System</span>
            </div>
        </div>
    </nav>

    <!-- Manual Attendance Container -->
    <div class="flex flex-1 items-center justify-center mt-12">
        <div class="bg-white text-gray-800 p-10 rounded-xl shadow-lg w-96 text-center">
            <h2 class="text-3xl font-extrabold text-blue-600 mb-3">Manual Attendance</h2>
            <p class="text-gray-600 mb-6">Course: <?php echo $course_id; ?> | Section: <?php echo $section_id; ?></p>
            <p class="text-gray-600 mb-6">Date: <?php echo $date; ?></p>
            <div class="border-t-4 border-blue-500 w-20 mx-auto mb-6"></div>

            <!-- Form to mark attendance -->
            <form method="POST">
                <div class="text-left">
                    <?php foreach ($students as $student) { ?>
                        <label class="block mb-2">
                            <input type="checkbox" name="present_students[]" value="<?php echo $student['sid']; ?>" class="mr-2">
                            <?php echo $student['name']; ?> (<?php echo $student['sid']; ?>)
                        </label>
                    <?php } ?>
                </div>
                <button type="submit" class="bg-blue-600 text-white py-3 rounded-lg w-full font-semibold transition duration-300 hover:bg-blue-700 hover:shadow-xl mt-4">Submit Attendance</button>
            </form>

            <!-- Back Button with updated functionality -->
            <a href="../faculty_course.php?course_id=<?php echo $course_id; ?>&section_id=<?php echo $section_id; ?>">
                <button class="bg-gray-600 text-white py-3 rounded-lg w-full font-semibold transition duration-300 hover:bg-gray-700 hover:shadow-xl mt-4">Back</button>
            </a>
        </div>
    </div>

</body>
</html>
