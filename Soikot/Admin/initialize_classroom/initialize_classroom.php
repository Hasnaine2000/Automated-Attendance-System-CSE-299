<?php
session_start();
require_once "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST["course_id"];
    $section_id = $_POST["section_id"];
    $fid = $_POST["fid"];
    $students = $_POST["sid"]; // Should be an array

    // Debugging: Check if $_POST data is received correctly
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    if (!isset($course_id, $section_id, $fid, $students) || !is_array($students)) {
        die("Invalid form submission. Please select students properly.");
    }

    // Prepare SQL statement
    $sql = "INSERT INTO classroom (course_id, section_id, fid, sid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    // Insert each selected student as a separate row
    foreach ($students as $student_id) {
        $stmt->bind_param("sisi", $course_id, $section_id, $fid, $student_id);
        if (!$stmt->execute()) {
            die("Insert Error: " . $stmt->error);
        }
    }

    echo "<script>alert('Classroom Initialized Successfully'); window.location='../dashboard.php';</script>";

    $stmt->close();
    $conn->close();
}
?>
