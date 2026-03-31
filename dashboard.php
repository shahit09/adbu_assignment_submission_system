<?php
session_start();
include "config/db.php";

// 🔐 Protect page (must login)
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Get logged in user name
$result = $conn->query("SELECT name FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo $user['name']; ?> 👋</h2>
    <p>Role: <strong><?php echo ucfirst($role); ?></strong></p>

    <a href="logout.php">Logout</a>
    <hr>

<?php
// ================= FACULTY DASHBOARD =================
if($role == 'faculty'){
?>

    <h3>Create Assignment</h3>
    <form method="POST" action="create_assignment.php">
        <input type="text" name="title" placeholder="Assignment Title" required><br><br>
        <textarea name="description" placeholder="Assignment Description" required></textarea><br><br>
        <button type="submit" name="create">Create</button>
    </form>

    <hr>
    <h3>Your Assignments</h3>

    <?php
    $assignments = $conn->query("SELECT * FROM assignments WHERE created_by='$user_id'");
    while($row = $assignments->fetch_assoc()){
        echo "<p><strong>".$row['title']."</strong> 
              <a href='view_submissions.php?id=".$row['id']."'>View Submissions</a></p>";
    }
    ?>

<?php
}
// ================= STUDENT DASHBOARD =================
else if($role == 'student'){
?>

    <h3>Available Assignments</h3>

    <?php
    $assignments = $conn->query("SELECT * FROM assignments");

    while($row = $assignments->fetch_assoc()){
    ?>

        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <h4><?php echo $row['title']; ?></h4>
            <p><?php echo $row['description']; ?></p>

            <form method="POST" action="submit_assignment.php" enctype="multipart/form-data">
                <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
                <input type="file" name="file" accept=".zip" required>
                <button type="submit" name="submit">Submit ZIP</button>
            </form>
        </div>

    <?php
    }
    ?>

    <hr>
    <h3>Your Submissions & Feedback</h3>

    <?php
    $submissions = $conn->query("
        SELECT assignments.title, submissions.grade, submissions.feedback 
        FROM submissions 
        JOIN assignments ON submissions.assignment_id = assignments.id
        WHERE submissions.student_id='$user_id'
    ");

    while($row = $submissions->fetch_assoc()){
        echo "<p><strong>".$row['title']."</strong><br>
              Grade: ".$row['grade']."<br>
              Feedback: ".$row['feedback']."</p><hr>";
    }
    ?>

<?php
}
?>

</div>

</body>
</html>