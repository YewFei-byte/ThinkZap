<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    die("Invalid request: No survey ID provided.");
}

$query = "DELETE FROM survey_questions WHERE survey_id = $id";

if (mysqli_query($connection, $query)) {
    header("Location: Survey_Management.php");
    exit;
} else {
    die("Error deleting survey question: " . mysqli_error($connection));
}

mysqli_close($connection);
?>
