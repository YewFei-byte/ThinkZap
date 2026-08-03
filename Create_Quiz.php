<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_question = mysqli_real_escape_string($connection, $_POST['quiz_question']);
    $quiz_type = mysqli_real_escape_string($connection, $_POST['quiz_type']);
    $quiz_option = isset($_POST['quiz_option']) && $quiz_type === 'objective' ? json_encode(explode(',', $_POST['quiz_option'])) : null;
    $correct_answer = mysqli_real_escape_string($connection, $_POST['correct_answer']);
    $quiz_level = mysqli_real_escape_string($connection, $_POST['quiz_level']);
    $quiz_subject = mysqli_real_escape_string($connection, $_POST['quiz_subject']);

    if ($quiz_type === 'objective') {
        $options = explode(',', $_POST['quiz_option']);
        if (count($options) !== 3) {
            die("Objective questions must have exactly 3 options.");
        }
    }

    if (empty($correct_answer)) {
        die("Correct answer cannot be null.");
    }

    $query = "INSERT INTO quiz_question (quiz_question, quiz_type, quiz_option, correct_answer, quiz_level, quiz_subject) 
              VALUES ('$quiz_question', '$quiz_type', '$quiz_option', '$correct_answer', '$quiz_level', '$quiz_subject')";

    if (!mysqli_query($connection, $query)) {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz Question</title>
    <link rel="stylesheet" href="quiz.css">
</head>
<body>
    <img src="Logo.png" alt="" class="logo">
    
    <div class="back-container">
        <a href="adminPage.php">
            <img src="back.png" alt="Back" class="back-btn">
        </a>
    </div>
    <div class="container">
        <form method="POST" onsubmit="return confirmSave();">
            <h2>Create Quiz Question</h2>
            <label for="quiz_subject">Subject:</label>
            <select id="quiz_subject" name="quiz_subject" required>
                <option value="Math">Math</option>
                <option value="English">English</option>
            </select>

            <label for="quiz_level">Quiz Level:</label>
            <select id="quiz_level" name="quiz_level" required>
                <option value="primary">Primary</option>
                <option value="secondary">Secondary</option>
            </select>

            <label for="quiz_type">Question Type:</label>
            <select id="quiz_type" name="quiz_type" required>
                <option value="objective" selected>Objective</option>
                <option value="subjective">Subjective</option>
            </select>

            <label for="quiz_question">Question:</label>
            <input type="text" id="quiz_question" name="quiz_question" required>

            <div id="options-container" style="display: block;">
                <label for="quiz_option">Options (exactly 3, comma-separated):</label>
                <input type="text" id="quiz_option" name="quiz_option" required>
            </div>

            <label for="correct_answer">Correct Answer:</label>
            <input type="text" id="correct_answer" name="correct_answer" placeholder="Enter the correct answer" required>

            <button type="submit">Add Question</button>
        </form>
    </div>

    <script>
        const quizType = document.getElementById("quiz_type");
        const optionsContainer = document.getElementById("options-container");
        const quizOptionInput = document.getElementById("quiz_option");

        quizType.addEventListener("change", function () {
            if (quizType.value === "objective") {
                optionsContainer.style.display = "block";
                quizOptionInput.required = true;
            } else {
                optionsContainer.style.display = "none";
                quizOptionInput.required = false;
                quizOptionInput.value = "";
            }
        });

        function confirmSave() {
            return confirm("Are you sure you want to save this question?");
        }
    </script>
</body>
</html>
