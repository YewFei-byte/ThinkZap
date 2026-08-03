<?php
session_start();
if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}

include('connection.php');

$userName = $_SESSION['userName'];

$deleteAnswersQuery = "DELETE FROM tblanswer WHERE name = ?";
$stmt = mysqli_prepare($connection, $deleteAnswersQuery);
mysqli_stmt_bind_param($stmt, 's', $userName);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$deleteQuizzesQuery = "DELETE FROM quiz_question WHERE quiz_id IN (SELECT DISTINCT quiz_id FROM tblanswer WHERE name = ?)";
$stmt = mysqli_prepare($connection, $deleteQuizzesQuery);
mysqli_stmt_bind_param($stmt, 's', $userName);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($connection);


echo "History cleared successfully.";
?>
