<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['quiz_id'])) {
    $id = intval($_GET['quiz_id']);
} else {
    die("Invalid request: No quiz ID provided.");
}

$query = "DELETE FROM quiz_question WHERE quiz_id = $id";

if (mysqli_query($connection, $query)) {
    header("Location: Quiz_Management.php");
    exit;
} else {
    die("Error deleting quiz question: " . mysqli_error($connection));
}

mysqli_close($connection);
?>
