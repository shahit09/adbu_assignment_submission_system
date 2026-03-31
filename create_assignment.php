<?php
session_start();
include "config/db.php";

// 🔐 Protect Page
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// 👩‍🏫 Only Faculty Allowed
if($_SESSION['role'] != 'faculty'){
    header("Location: dashboard.php");
    exit();
}

// 📌 Handle Form Submission
if(isset($_POST['create'])){

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $faculty_id = $_SESSION['user_id'];

    // Basic validation
    if(!empty($title) && !empty($description)){

        $stmt = $conn->prepare("INSERT INTO assignments (title, description, created_by) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $description, $faculty_id);

        if($stmt->execute()){
            header("Location: dashboard.php?success=AssignmentCreated");
            exit();
        } else {
            echo "Error creating assignment.";
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
?>