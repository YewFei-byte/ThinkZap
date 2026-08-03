<?php
session_start();
if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Think Zap - Create questions your way</title>
    <link rel="stylesheet" href="styleHP.css">
</head>
<style>

</style>
<body>
    <header>
        <div class="logo">
            <div class="img">
                <img src="ThinkZapLogo.png" width="400px">
            </div>
            <div>
            <a href="accountDetailsHP.php" id="setting"> 
            <img src="settings.png" height="60px" width="60px"> 
            </a> 
            </div>
        </div>
    </header>

    <section class="content">
        <div class="container">
            <h2>Welcome to Think Zap, <?php echo $_SESSION['userName'] ?>!</h2>
            <p>Are you ready to create your own quiz? It's simple, fun, and engaging. Whether for education or entertainment, we've got the tools you need to make quizzes easily!</p>

            <div class="cta-buttons">
                <a href="quiz.php" class="button">Start Quiz</a>
                <a href="survey.php" class="button">Start Survey</a>
                <a href="learnMore.php" class="button">Learn More</a>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Think Zap. All rights reserved.</p>
        </div>
    </footer>
    <footer class="about-us-footer">
        <div class="footer-content">
            <h2>About Us</h2>
            <table border="0">
                <tr>
                    <td>- <a href="CYQ.php">Chong Yin Quan</a></td>
                    <td>- <a href="YHC.php">Yong Hong Chang</a></td>
                    <td>- <a href="LYH.php">Lai Yik Hong</a></td>
                    <td>- <a href="LWH.php">Lim Wei Han</a></td>
                    <td>- <a href="LYF.php">Lam Yew Fei</a></td>
                </tr>
            </table>
        </div>
    </footer>

</body>
</html>
