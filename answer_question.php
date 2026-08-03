<?php
session_start();

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}

include('connection.php');

$questionId = isset($_GET['question_id']) ? $_GET['question_id'] : 1;

$query = "SELECT * FROM quiz_question WHERE quiz_id = ? LIMIT 1";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $questionId);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $quiz_id, $question_text, $quiz_subject, $quiz_type, $quiz_level);
mysqli_stmt_fetch($stmt);

$query_total = "SELECT COUNT(*) FROM quiz_question";
$stmt_total = mysqli_prepare($connection, $query_total);
mysqli_stmt_execute($stmt_total);
mysqli_stmt_bind_result($stmt_total, $total_questions);
mysqli_stmt_fetch($stmt_total);

$next_question_id = $questionId < $total_questions ? $questionId + 1 : 1;

mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt_total);
mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Question</title>
</head>
<body>
    <h2>Question: <?php echo htmlspecialchars($question_text); ?></h2>
    <form method="POST" action="submit_answer.php">
        <label for="answer">Your Answer:</label>
        <input type="text" name="answer" id="answer" required>
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <input type="submit" value="Submit Answer">
    </form>

    <br>
    <a href="answer_question.php?question_id=<?php echo $next_question_id; ?>">Next Question</a>
</body>
</html>
