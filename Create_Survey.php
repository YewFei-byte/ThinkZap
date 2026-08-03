<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['adminName'];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Builder</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
<img src="Logo.png" alt="" class="logo">

<div class="back-container">
    <a href="adminPage.php">
        <img src="back.png" alt="Back" class="back-btn">
    </a>
</div>

<h2>Build Your Survey</h2>
<div id="survey-builder">
    <label for="question-type">Select Question Type:</label>
    <select id="question-type">
        <option value="text">Short Answer</option>
        <option value="textarea">Long Answer</option>
        <option value="radio">Single Choice (Radio)</option>
        <option value="checkbox">Multiple Choice (Checkbox)</option>
    </select>

    <label for="question-text">Enter Question Text:</label>
    <input type="text" id="question-text" placeholder="Type your question here">

    <div id="options-container" style="display: none;">
        <input type="text" id="option-input" placeholder="Enter option">
        <button type="button" onclick="addOption()">Add</button>
        <ul id="options-list"></ul>
    </div>

    <button type="button" onclick="addQuestion()">Add Question</button>
</div>

<h2>Survey Preview</h2>
<form id="survey-form" method="POST" action="Survey_confirm.php">
    <input type="hidden" name="questions" id="questions-input">
    <button type="submit">Save Survey</button>
</form>

<script>
    const questionType = document.getElementById("question-type");
    const questionText = document.getElementById("question-text");
    const optionsContainer = document.getElementById("options-container");
    const optionsList = document.getElementById("options-list");
    const questionsInput = document.getElementById("questions-input");
    const surveyForm = document.getElementById("survey-form");

    let questions = [];
    let options = [];

    questionType.addEventListener("change", function () {
        if (questionType.value === "radio" || questionType.value === "checkbox") {
            optionsContainer.style.display = "block";
        } else {
            optionsContainer.style.display = "none";
            options = [];
            optionsList.innerHTML = "";
        }
    });

    function addOption() {
        const optionInput = document.getElementById("option-input");
        if (optionInput.value) {
            options.push(optionInput.value);
            const li = document.createElement("li");
            li.innerHTML = `<span>${optionInput.value}</span> <button type="button" onclick="removeOption('${optionInput.value}')">X</button>`;
            optionsList.appendChild(li);
            optionInput.value = "";
        }
    }

    function removeOption(optionValue) {
        options = options.filter(option => option !== optionValue);
        optionsList.innerHTML = "";
        options.forEach(option => {
            const li = document.createElement("li");
            li.innerHTML = `<span>${option}</span> <button type="button" onclick="removeOption('${option}')">X</button>`;
            optionsList.appendChild(li);
        });
    }

    function addQuestion() {
        if (!questionText.value.trim()) {
            alert("Please enter a question text before adding.");
            return; 
        }

        const question = {
            text: questionText.value,
            type: questionType.value,
            options: questionType.value === "radio" || questionType.value === "checkbox" ? [...options] : null,
        };

        questions.push(question);

        questionText.value = "";
        questionType.value = "text"; 
        options = [];
        optionsList.innerHTML = "";
        optionsContainer.style.display = "none";

        questionsInput.value = JSON.stringify(questions);

        const questionContainer = document.createElement("div");
        questionContainer.classList.add("question-container");

        const questionLabel = document.createElement("label");
        questionLabel.textContent = question.text;
        questionContainer.appendChild(questionLabel);

        if (question.type === "text") {
            const input = document.createElement("input");
            input.type = "text";
            input.name = question.text;
            questionContainer.appendChild(input);
        } else if (question.type === "textarea") {
            const textarea = document.createElement("textarea");
            textarea.name = question.text;
            questionContainer.appendChild(textarea);
        } else if (question.type === "radio" || question.type === "checkbox") {
            question.options.forEach(option => {
                const optionContainer = document.createElement("div");
                const input = document.createElement("input");
                input.type = question.type;
                input.name = question.text;
                input.value = option;

                const label = document.createElement("label");
                label.textContent = option;
                optionContainer.appendChild(input);
                optionContainer.appendChild(label);
                questionContainer.appendChild(optionContainer);
            });
        }

        const removeButton = document.createElement("img");
        removeButton.src = "remove.png";
        removeButton.alt = "Remove";
        removeButton.classList.add("remove-question");
        removeButton.onclick = () => removeQuestion(question, questionContainer);
        questionContainer.appendChild(removeButton);

        surveyForm.insertBefore(questionContainer, surveyForm.lastElementChild);
    }

    function removeQuestion(question, questionContainer) {
        questions = questions.filter(q => q.text !== question.text);
        questionsInput.value = JSON.stringify(questions);
        questionContainer.remove();
    }

    surveyForm.addEventListener("submit", function (event) {
    event.preventDefault(); 

    if (confirm("Are you sure you want to save the survey?")) {
        const formData = new FormData(surveyForm);
        fetch(surveyForm.action, {
            method: surveyForm.method,
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            console.log("Server Response:", data);
            alert(data);

            // Clear the preview questions
            const questionContainers = document.querySelectorAll(".question-container");
            questionContainers.forEach(container => container.remove());

            // Reset the form
            questions = [];
            questionsInput.value = JSON.stringify(questions);
        })
        .catch(error => {
            console.error("Fetch error:", error);
            alert("Error saving survey: " + error.message);
        });
    } else {
        alert("You can continue editing your survey.");
    }
});
</script>
</body>
</html>
