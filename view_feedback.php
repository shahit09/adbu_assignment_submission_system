<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT assignments.title, submissions.grade, submissions.feedback 
    FROM submissions 
    JOIN assignments ON submissions.assignment_id = assignments.id
    WHERE submissions.student_id='$student_id'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Feedback</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
<h2>Your Feedback</h2>

<?php while($row = $result->fetch_assoc()){ ?>
    <div class="card">
        <h3><?php echo $row['title']; ?></h3>
        <p><strong>Grade:</strong> <?php echo $row['grade']; ?></p>
        <p><strong>Feedback:</strong> <?php echo $row['feedback']; ?></p>
    </div>
<?php } ?>

<br>
<a href="dashboard.php">Back</a>
</div>

</body>
</html>