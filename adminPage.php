<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['adminName']) ){
   header('location:login.php');
}

$adminName = $_SESSION['adminName'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminPage</title>
    <link rel="stylesheet" href="style3.css">
    
</head>
<body>
    <img src="logo.png" alt="Logo" class="logo">
    <div class="container">
        <div class="content">
            <h2>Hi, <span>admin</span></h2>
            <h1>Welcome <span><?php echo $_SESSION['adminName'] ?></span></h1>

            <div class="buttons">
            <button onclick="location.href='Create_Survey.php'">Create Survey Question</button>
            <button onclick="location.href='Survey_Management.php'">Survey Management</button>
            <button onclick="location.href='Create_Quiz.php'">Create Quiz</button>
            <button onclick="location.href='Quiz_Management.php'">Quiz Management</button>
            <button onclick="location.href='Feedback.php'">Survey Feedback</button>
        </div>
        </div>
    <div class="logout-container" style="position: absolute; bottom: 10px; left: 10px;">
    <button onclick="confirmLogout()">Log Out</button>
    </div>

    <script>
    function confirmLogout() {
        if (confirm('Are you sure you want to log out?')) {
            location.href = 'login.php';
        }
    }
</script>
    </div>
    </div>
</body>
</html>