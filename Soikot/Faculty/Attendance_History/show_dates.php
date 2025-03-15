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
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .date-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .date-button:hover {
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
        <h2>Attendance History</h2>
        <?php if (count($dates) > 0): ?>
            <?php foreach ($dates as $date): ?>
                <form action="attendance_details.php" method="GET">
                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                    <input type="hidden" name="date" value="<?php echo $date['date']; ?>">
                    <button type="submit" class="date-button"> <?php echo $date['date']; ?> </button>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No attendance records found.</p>
        <?php endif; ?>
        <a href="../faculty_dashboard.php"><button class="back-btn">Back</button></a>
    </div>
</body>
</html>
