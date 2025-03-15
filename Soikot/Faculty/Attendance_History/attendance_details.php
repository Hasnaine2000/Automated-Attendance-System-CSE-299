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
        .present { color: green; }
        .absent { color: red; }
        .back-btn {
            background: gray;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: darkgray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Attendance on <?php echo $date; ?></h2>
        <table border="1" width="100%">
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Status</th>
            </tr>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['sid']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td class="<?php echo in_array($student['sid'], $present_sids) ? 'present' : 'absent'; ?>">
                        <?php echo in_array($student['sid'], $present_sids) ? 'Present' : 'Absent'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="attendance_history.php?course_id=<?php echo $course_id; ?>&section_id=<?php echo $section_id; ?>">
            <button class="back-btn">Back</button>
        </a>
    </div>
</body>
</html>
