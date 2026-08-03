<?php
session_start();

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}

include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer']) && isset($_POST['quiz_id'])) {
    $userName = $_SESSION['userName'];
    $answer = $_POST['answer'];
    $quiz_id = $_POST['quiz_id'];

    $query_insert = "INSERT INTO tblanswer (name, quiz_id, Answer, Date) VALUES (?, ?, ?, NOW())";
    $stmt_insert = mysqli_prepare($connection, $query_insert);
    mysqli_stmt_bind_param($stmt_insert, 'sis', $userName, $quiz_id, $answer);
    mysqli_stmt_execute($stmt_insert);
    mysqli_stmt_close($stmt_insert);
    $query_next = "SELECT COUNT(*) FROM quiz_question WHERE quiz_id > ?";
    $stmt_next = mysqli_prepare($connection, $query_next);
    mysqli_stmt_bind_param($stmt_next, 'i', $quiz_id);
    mysqli_stmt_execute($stmt_next);
    mysqli_stmt_bind_result($stmt_next, $next_question_count);
    mysqli_stmt_fetch($stmt_next);

    $next_question_id = ($next_question_count > 0) ? $quiz_id + 1 : 1;

    mysqli_stmt_close($stmt_next);
    mysqli_close($connection);

    header("Location: answer_question.php?question_id=" . $next_question_id);
    exit;
} else {
    header("Location: answer_question.php");
    exit;
}
?>
