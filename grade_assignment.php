<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty'){
    header("Location: login.php");
    exit();
}

if(isset($_POST['grade'])){

    $submission_id = $_POST['submission_id'];
    $grade = $_POST['grade'];
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("UPDATE submissions SET grade=?, feedback=? WHERE id=?");
    $stmt->bind_param("ssi", $grade, $feedback, $submission_id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php?graded=success");
    exit();
}
?>