<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styleQ.css">
</head>
<body>
    <h1><a href="homePage.php"> 
        <img src="ThinkZapLogo.png" style="width: 250px;">
    </a></h1>

    <div class="body-content">
        <div class="quiz-selection">
            <p>Choose a Quiz</p> 
        </div>

        <div class="center-container">
            <div id="obj"><a href="Objective.php" id="obj" >Objective</a></div>
            <div id="sub"><a href="Subjective.php" id="sub"> Subjective </a></div>
            <div id="mix"><a href="Mix.php" id="mix">Mix(Obj/Sub)</a></div>
        </div>

        <a href="accountDetailsQ.php" id="setting"> 
            <img src="settings.png" height="60px" width="60px"> 
        </a> 
    </div>

    <footer class="about-us-footer">
        <div class="footer-content">
            <h3>About Us</h3>
            <table border="0">
                <tr>
                    <td><a href="CYQ.php">- Chong Yin Quan</a></td>
                    <td><a href="YHC.php">- Yong Hong Chang</a></td>
                    <td><a href="LYH.php">- Lai Yik Hong</a></td>
                    <td><a href="LWH.php">- Lim Wei Han</a></td>
                    <td><a href="LYF.php">- Lam Yew Fei</a></td>
                </tr>
            </table>
        </div>
    </footer>
</body>
</html>
