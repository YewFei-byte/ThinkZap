<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['quiz_id'])) {
    die("Invalid request. No quiz ID specified.");
}

$quiz_id = intval($_GET['quiz_id']);

$query = "SELECT * FROM quiz_question WHERE quiz_id = $quiz_id";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Quiz question not found.");
}

$quiz = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_subject = mysqli_real_escape_string($connection, $_POST['quiz_subject']);
    $quiz_question = mysqli_real_escape_string($connection, $_POST['quiz_question']);
    $quiz_type = mysqli_real_escape_string($connection, $_POST['quiz_type']);
    $quiz_option = ($quiz_type === 'objective') ? json_encode(explode(',', $_POST['quiz_option'])) : null;
    $correct_answer = mysqli_real_escape_string($connection, $_POST['correct_answer']);
    $quiz_level = mysqli_real_escape_string($connection, $_POST['quiz_level']);

    if (empty($correct_answer)) {
        die("Correct answer cannot be null.");
    }

    $update_query = "UPDATE quiz_question 
                     SET quiz_subject = '$quiz_subject',
                         quiz_question = '$quiz_question',
                         quiz_type = '$quiz_type',
                         quiz_option = '$quiz_option',
                         correct_answer = '$correct_answer',
                         quiz_level = '$quiz_level'
                     WHERE quiz_id = $quiz_id";

    if (mysqli_query($connection, $update_query)) {
        header('Location: Quiz_Management.php');
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz Question</title>
    <link rel="stylesheet" href="Edit.css">
    <script>
        function toggleOptions() {
            const quizType = document.getElementById('quiz_type').value;
            const optionsField = document.getElementById('options-container');
            optionsField.style.display = quizType === 'objective' ? 'block' : 'none';
        }

        window.onload = function() {
            toggleOptions();
        };
    </script>
</head>
<body>
    <img src="logo.png" alt="Logo" class="logo">
    <div class="container">
        <h2>Edit Quiz Question</h2>
        <form method="POST">
            <label for="quiz_subject">Quiz Subject:</label>
            <select id="quiz_subject" name="quiz_subject" required>
                <option value="English" <?php if ($quiz['quiz_subject'] == 'English') echo 'selected'; ?>>English</option>
                <option value="Math" <?php if ($quiz['quiz_subject'] == 'Math') echo 'selected'; ?>>Math</option>
            </select>

            <label for="quiz_question">Quiz Question:</label>
            <input type="text" id="quiz_question" name="quiz_question" value="<?php echo htmlspecialchars($quiz['quiz_question']); ?>" required>

            <label for="quiz_type">Quiz Type:</label>
            <select id="quiz_type" name="quiz_type" onchange="toggleOptions()" required>
                <option value="objective" <?php if ($quiz['quiz_type'] == 'objective') echo 'selected'; ?>>Objective</option>
                <option value="subjective" <?php if ($quiz['quiz_type'] == 'subjective') echo 'selected'; ?>>Subjective</option>
            </select>

            <div id="options-container" style="display: <?php echo $quiz['quiz_type'] === 'objective' ? 'block' : 'none'; ?>;">
                <label for="quiz_option">Options (comma-separated):</label>
                <input type="text" id="quiz_option" name="quiz_option" value="<?php echo implode(',', json_decode($quiz['quiz_option'], true) ?: []); ?>">
            </div>

            <label for="correct_answer">Correct Answer:</label>
            <input type="text" id="correct_answer" name="correct_answer" value="<?php echo htmlspecialchars($quiz['correct_answer']); ?>" required>

            <label for="quiz_level">Quiz Level:</label>
            <input type="text" id="quiz_level" name="quiz_level" value="<?php echo htmlspecialchars($quiz['quiz_level']); ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>
    <div class="back-container">
        <a href="Quiz_Management.php">
            <img src="back.png" alt="Back" class="back-btn">
        </a>
    </div>
</body>
</html>
