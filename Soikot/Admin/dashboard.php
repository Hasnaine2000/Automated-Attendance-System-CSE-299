<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, Admin!</h2>
        
       

        <!-- Add Students Button -->
        <a href="add_students/index.html"><button class="button">Add Students</button></a> 
        
        <a href="initialize_classroom/classroom_initializer.php"><button class="button">Classroom Initializer</button></a> 
        

        <!-- Logout Button -->
        <a href="logout.php"><button class="logout-btn">Logout</button></a>
    </div>
</body>
</html>
