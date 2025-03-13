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
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f4f4f4;
        }

        .dashboard-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
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

        .logout-btn {
            background: red;
        }

        .logout-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, Faculty!</h2>
        
        <h3>Your Courses:</h3>
        <?php while ($row = $result->fetch_assoc()): ?>
            <form action="faculty_course.php" method="GET">
                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($row['course_id']); ?>">
                <input type="hidden" name="section_id" value="<?php echo htmlspecialchars($row['section_id']); ?>">
                <button type="submit">Course: <?php echo htmlspecialchars($row['course_id']); ?> | Section: <?php echo htmlspecialchars($row['section_id']); ?></button>
            </form>
        <?php endwhile; ?>
        <a href="logout.php"><button class="logout-btn">Logout</button></a>
    </div>
</body>
</html>