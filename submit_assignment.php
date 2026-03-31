<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: login.php");
    exit();
}

if(isset($_POST['submit'])){

    $assignment_id = $_POST['assignment_id'];
    $student_id = $_SESSION['user_id'];

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    $allowed = ['zip'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        die("Only ZIP files allowed.");
    }

    if($size > 10 * 1024 * 1024){
        die("File size must be under 10MB.");
    }

    $newname = time() . "_" . $file;
    move_uploaded_file($tmp, "uploads/" . $newname);

    $stmt = $conn->prepare("INSERT INTO submissions (assignment_id, student_id, file) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $assignment_id, $student_id, $newname);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?success=submitted");
    exit();
}
?>