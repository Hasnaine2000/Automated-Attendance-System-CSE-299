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
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f4f4f4;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        select[multiple] {
            height: 150px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Initialize Classroom</h2>
        <form action="initialize_classroom.php" method="post">
            <input type="text" name="course_id" placeholder="Enter Course ID" required>
            <input type="number" name="section_id" placeholder="Enter Section ID" required>

            <!-- Faculty Selection -->
            <select name="fid" required>
                <option value="">Select Faculty ID</option>
                <?php while ($faculty = $faculty_result->fetch_assoc()) { ?>
                    <option value="<?= $faculty['fid'] ?>"><?= $faculty['fid'] ?></option>
                <?php } ?>
            </select>

            <!-- Student Selection (Multiple) -->
            <select name="sid[]" multiple required>
                <?php while ($student = $student_result->fetch_assoc()) { ?>
                    <option value="<?= $student['sid'] ?>"><?= $student['name'] ?></option>
                <?php } ?>
            </select>

            <button type="submit" name="initialize">Initialize Classroom</button>
        </form>
    </div>
</body>
</html>
