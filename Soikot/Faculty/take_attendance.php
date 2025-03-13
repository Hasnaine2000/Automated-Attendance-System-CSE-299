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
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        pre {
            background: #eaeaea;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
            overflow-x: auto;
        }
        .back-btn {
            background: gray;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }
        .back-btn:hover {
            background: darkgray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Taking Attendance...</h2>
        <pre><?php echo htmlspecialchars($output); ?></pre>
        <a href="faculty_dashboard.php"><button class="back-btn">Back</button></a>
    </div>
</body>
</html>
