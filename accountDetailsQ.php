<?php
session_start();
if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}

include('connection.php');

$userName = $_SESSION['userName'];
$query = "
    SELECT 
        a.question_text, 
        a.Answer, 
        a.Mark, 
        a.Date, 
        q.quiz_subject, 
        q.quiz_type, 
        q.quiz_level
    FROM tblanswer a
    JOIN quiz_question q ON a.question_id = q.quiz_id  -- Correct join based on question_id and quiz_id
    WHERE a.name = ? 
    ORDER BY a.Date DESC
";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 's', $userName);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $question_text, $answer, $mark, $date, $quiz_subject, $quiz_type, $quiz_level);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" href="styleAD.css"> 
</head>
<body>
    <div class="header">
        <a href="javascript:history.back()" class="back-button">
            <img src="back_button.png" alt="Back Button" width="40px" height="40px">
        </a>

        <div class="logo-container">
            <a href="homePage.php" onclick="return confirmNavigation();">
                <img src="ThinkZapLogo.png" style="width:200px" alt="Thinkzap Logo">
            </a>
        </div>
    </div>

    <h2>Account Details</h2>

    <div class="Settings">
        <a href="javascript:void(0);" onclick="confirmLogout()">
            <img src="logOut.png" width="200px" alt="" class="top-right-image">
        </a>
    </div>

    <div class="ProfilePic">
        <img src="profile.png" alt="" class="image">
        <div class="text">
            <h2>Username: <?php echo $_SESSION['userName'] ?> </h2>
        </div>
    </div>

    <h3>Quiz History</h3>
    <table class="quiz-table">
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Marks</th>
                <th>Quiz Subject</th>
                <th>Quiz Type</th>
                <th>Quiz Level</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while (mysqli_stmt_fetch($stmt)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($question_text) . "</td>";
                echo "<td>" . htmlspecialchars($answer) . "</td>";
                echo "<td>" . htmlspecialchars($mark) . "</td>";
                echo "<td>" . htmlspecialchars($quiz_subject) . "</td>";
                echo "<td>" . htmlspecialchars($quiz_type) . "</td>";
                echo "<td>" . htmlspecialchars($quiz_level) . "</td>";
                echo "<td>" . htmlspecialchars($date) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <button id="clearHistoryBtn" onclick="confirmClearHistory()">Clear History</button>     

<script>
    function confirmClearHistory() {
        var confirmation = confirm("Are you sure you want to clear your quiz history?");
        if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'clear_history.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Your quiz history has been cleared.');
                    window.location.reload();
                } else {
                    alert('An error occurred while clearing the history.');
                }
            };
            xhr.send();
        }
    }
</script>

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

    <script>
        function confirmLogout() {
            var confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = 'login.php';
            }
        }
    </script>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
