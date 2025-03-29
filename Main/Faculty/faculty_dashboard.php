<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION["fid"])) {
    header("Location: faculty_login.php");
    exit();
}

$fid = $_SESSION["fid"];
$sql = "SELECT DISTINCT course_id, section_id FROM classroom WHERE fid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fid);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-900 text-white">

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

    <div class="container mx-auto p-6">
        <!-- Course Container -->
        <div class="bg-white bg-opacity-80 text-gray-800 p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Your Courses</h2>
            
            <!-- Course Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <form action="faculty_course.php" method="GET" class="w-full">
                        <button type="submit" class="w-full p-4 text-left bg-white rounded-md shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            <div class="bg-blue-500 h-24"></div>
                            <div class="p-4">
                                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($row['course_id']); ?>">
                                <input type="hidden" name="section_id" value="<?php echo htmlspecialchars($row['section_id']); ?>">
                                <h2 class="text-blue-600 font-bold"><?php echo htmlspecialchars($row['course_id']); ?> - Section <?php echo htmlspecialchars($row['section_id']); ?></h2>
                                <p class="text-gray-500">Spring 2025</p>
                            </div>
                        </button>
                    </form>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

</body>
</html>
