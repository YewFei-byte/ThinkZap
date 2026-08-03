<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request. No ID specified.");
}

$id = intval($_GET['id']);

$query = "SELECT * FROM survey_questions WHERE survey_id = $id";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Survey question not found.");
}

$question = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = mysqli_real_escape_string($connection, $_POST['question_text']);
    $question_type = mysqli_real_escape_string($connection, $_POST['question_type']);
    $options = json_encode(explode(',', $_POST['options']));

    $update_query = "UPDATE survey_questions 
                     SET question_text = '$question_text', 
                         question_type = '$question_type', 
                         options = '$options' 
                     WHERE survey_id = $id";

    if (mysqli_query($connection, $update_query)) {
        header('Location: Survey_Management.php');
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
    <title>Edit Survey Question</title>
    <link rel="stylesheet" href="Edit.css">
</head>
<body>
<img src="logo.png" alt="Logo" class="logo">
    <div class="container">
        <h2>Edit Survey Question</h2>
        <form method="POST">
            <label for="question_text">Question Text:</label>
            <input type="text" id="question_text" name="question_text" value="<?php echo htmlspecialchars($question['question_text']); ?>" required>
            
            <label for="question_type">Question Type:</label>
            <select id="question_type" name="question_type" required>
                <option value="short-answer" <?php if ($question['question_type'] == 'short-answer') echo 'selected'; ?>>Short Answer</option>
                <option value="long-answer" <?php if ($question['question_type'] == 'long-answer') echo 'selected'; ?>>Long Answer</option>
                <option value="multiple-choice" <?php if ($question['question_type'] == 'multiple-choice') echo 'selected'; ?>>Multiple Choice</option>
                <option value="checkbox" <?php if ($question['question_type'] == 'checkbox') echo 'selected'; ?>>Checkbox</option>
            </select>

            <label for="options">Options (comma-separated):</label>
            <input type="text" id="options" name="options" value="<?php echo implode(',', json_decode($question['options'], true) ?: []); ?>">

            <button type="submit">Update</button>
        </form>
    </div>
    <div class="back-container">
    <a href="Survey_Management.php">
        <img src="back.png" alt="Back" class="back-btn">
    </a>
    </div>
</body>
</html>
