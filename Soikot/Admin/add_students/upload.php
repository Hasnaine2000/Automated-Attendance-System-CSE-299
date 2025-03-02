<?php
$upload_dir = "uploads/";

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
    $filename = basename($_FILES["files"]["name"][$key]);
    move_uploaded_file($tmp_name, "$upload_dir/$filename");
}

// Run Python script after upload
$python_script = "python process_faces.py";  // Adjust path if needed
$output = shell_exec($python_script);

echo "Folder uploaded and Python script executed!";
?>
