<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

require_once "../db_connect.php";

// Fetch faculty members
$faculty_query = "SELECT fid FROM faculty";
$faculty_result = $conn->query($faculty_query);

// Fetch students
$student_query = "SELECT sid, name FROM student";
$student_result = $conn->query($student_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Initializer</title>
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

    <!-- Classroom Initializer Form Container -->
    <div class="flex flex-1 items-center justify-center mt-12">
        <div class="bg-white text-gray-800 p-10 rounded-xl shadow-lg w-96 text-center">
            <h2 class="text-3xl font-extrabold text-blue-600 mb-3">Initialize Classroom</h2>
            <div class="border-t-4 border-blue-500 w-20 mx-auto mb-6"></div>

            <form action="initialize_classroom.php" method="post">
                <!-- Course ID Input -->
                <input type="text" name="course_id" placeholder="Enter Course ID" required class="w-full p-3 mb-4 rounded-lg border border-gray-300 text-gray-800">

                <!-- Section ID Input -->
                <input type="number" name="section_id" placeholder="Enter Section ID" required class="w-full p-3 mb-4 rounded-lg border border-gray-300 text-gray-800">

                <!-- Faculty Selection -->
                <select name="fid" required class="w-full p-3 mb-4 rounded-lg border border-gray-300 text-gray-800">
                    <option value="">Select Faculty ID</option>
                    <?php while ($faculty = $faculty_result->fetch_assoc()) { ?>
                        <option value="<?= $faculty['fid'] ?>"><?= $faculty['fid'] ?></option>
                    <?php } ?>
                </select>

                <!-- Student Selection (Multiple) -->
                <select name="sid[]" multiple required class="w-full p-3 mb-4 rounded-lg border border-gray-300 text-gray-800" style="height: 100px;">
                    <?php while ($student = $student_result->fetch_assoc()) { ?>
                        <option value="<?= $student['sid'] ?>"><?= $student['name'] ?></option>
                    <?php } ?>
                </select>

                <!-- Submit Button -->
                <button type="submit" name="initialize" class="bg-blue-600 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:bg-blue-700 hover:shadow-lg">Initialize Classroom</button>
            </form>

            <!-- Back Button with Updated Style -->
            <a href="../dashboard.php" class="mt-4 inline-block bg-gray-600 hover:bg-gray-700 text-white py-3 rounded-lg w-full font-semibold transition-all duration-200 ease-in-out hover:shadow-lg">Back</a>
        </div>
    </div>

</body>

</html>
