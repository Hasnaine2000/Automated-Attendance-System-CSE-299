<?php
session_start();
require_once "db_connect.php";

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Options</title>
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
        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .back-btn {
            background: gray;
        }
        .back-btn:hover {
            background: darkgray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Course: <?php echo $course_id; ?> | Section: <?php echo $section_id; ?></h2>
        
        <form action="take_attendance.php" method="POST">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
            <button type="submit">Take Attendance</button>
        </form>
        
        <button onclick="alert('Manual Attendance coming soon!')">Manual Attendance</button>
        <button onclick="alert('Attendance History coming soon!')">Attendance History</button>
        
        <a href="faculty_dashboard.php"><button class="back-btn">Back</button></a>
    </div>
</body>
</html>
