<?php
include 'connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questions = json_decode($_POST['questions'], true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Invalid JSON data: " . json_last_error_msg());
    }

    foreach ($questions as $question) {
        $question_text = mysqli_real_escape_string($connection, $question['text']);
        $question_type = mysqli_real_escape_string($connection, $question['type']);
        $options = isset($question['options']) ? json_encode($question['options']) : null;
        $options = mysqli_real_escape_string($connection, $options);

        $query = "INSERT INTO survey_questions (question_text, question_type, options) 
                  VALUES ('$question_text', '$question_type', '$options')";

        if (!mysqli_query($connection, $query)) {
            error_log("Error inserting data: " . mysqli_error($connection));
            die("Error inserting data: " . mysqli_error($connection));
        }
    }

    echo "Survey saved successfully!";
    exit;
}

mysqli_close($connection);
?>
