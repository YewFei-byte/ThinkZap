<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$database = "rwdd2307";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : 'Guest';
$answer = isset($_POST['answer']) ? $_POST['answer'] : '';
$question_text = isset($_POST['question_text']) ? $_POST['question_text'] : '';
$mark = isset($_POST['mark']) ? $_POST['mark'] : 0;

$sql = "INSERT INTO tblanswer (name, Answer, question_text, Mark) 
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $name, $answer, $question_text, $mark);

if ($stmt->execute()) {
    echo "Answer inserted successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
