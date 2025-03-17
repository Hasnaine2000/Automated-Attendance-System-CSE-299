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
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .student-list {
            text-align: left;
            max-height: 300px;
            overflow-y: auto;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #218838;
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
        <h2>Manual Attendance</h2>
        <p><strong>Course:</strong> <?php echo $course_id; ?> | <strong>Section:</strong> <?php echo $section_id; ?></p>
        <p><strong>Date:</strong> <?php echo $date; ?></p>

        <form method="POST">
            <div class="student-list">
                <?php foreach ($students as $student) { ?>
                    <label>
                        <input type="checkbox" name="present_students[]" value="<?php echo $student['sid']; ?>">
                        <?php echo $student['name']; ?> (<?php echo $student['sid']; ?>)
                    </label><br>
                <?php } ?>
            </div>
            <button type="submit">Submit Attendance</button>
        </form>

        <a href="faculty_dashboard.php"><button class="back-btn">Back</button></a>
    </div>
</body>
</html>
