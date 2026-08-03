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
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssi", $name, $answer, $question_text, $mark);

if ($stmt->execute()) {
    echo "";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "rwdd2307";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT quiz_id, quiz_subject, quiz_question, correct_answer 
        FROM quiz_question 
        WHERE quiz_type = 'subjective' AND quiz_subject = 'Math' AND quiz_level = 'secondary'";
$result = $conn->query($sql);

$questions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
} else {
    echo "The question is not found.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thinkzap Quiz</title>
    <link rel="stylesheet" href="styleSMS.css">
</head>
<script>
        function confirmNavigation() {
            const userConfirmed = confirm("Are you sure you want to leave this page? Your progress may not be saved.");
            return userConfirmed;
        }
    </script>
</head>
<body>
    <div class="header">
        <div style="display: flex; align-items: center;">
            <a href="homePage.php" onclick="return confirmNavigation();">
                <img src="ThinkZapLogo.png" alt="Thinkzap Logo">
            </a>
        </div>
    </div>
    <div class="back">
        <a href="Subjective.php">
            <img src="back_button.png" alt="Return Logo">
        </a>
    </div>
    <div class="quiz-container">
        <?php foreach ($questions as $index => $question): ?>
            <div class="question-card <?= $index === 0 ? 'active' : ''; ?>" 
                 data-name="Q<?= $question['quiz_id']; ?>" 
                 data-correct="<?= htmlspecialchars($question['correct_answer']); ?>">
                <h2>Question <?= $index + 1; ?></h2>
                <h3><?= htmlspecialchars($question['quiz_question']); ?></h3>
                <textarea id="answer_<?= $question['quiz_id']; ?>" placeholder="Type your answer here..."></textarea>
                <button>Confirm</button>
                <div id="message_<?= $question['quiz_id']; ?>" class="message-box"></div>
            </div>
        <?php endforeach; ?>
        <div class="result-box" id="resultBox">
            <h2>Quiz Completed!</h2>
            <p>Your score: <span id="finalScore">0</span></p>
            <p>You got <span id="correctCount">0</span> correct out of <span id="totalQuestions"><?= count($questions); ?></span> questions.</p>
        </div>
    </div>

    <script>
    let score = 0;
let correctAnswers = 0;
let totalQuestions = 0;
let currentIndex = 0;

function loadQuiz() {
    const questionCards = document.querySelectorAll('.question-card');
    totalQuestions = questionCards.length;

    if (questionCards.length > 0) {
        questionCards[currentIndex].classList.add('active');
    }

    questionCards.forEach((card, index) => {
        const button = card.querySelector('button');
        
        button.addEventListener('click', function () {
            const questionId = card.getAttribute('data-name').substring(1);
            const correctAnswer = card.getAttribute('data-correct');
            const questionText = card.querySelector('h3').textContent;
            const userAnswer = document.getElementById('answer_' + questionId).value.trim();

            checkAnswer(questionId, correctAnswer, userAnswer);

            sendAnswerToDatabase(userAnswer, questionText, correctAnswer === userAnswer ? 5 : 0);

            button.disabled = true;

            setTimeout(() => {
                card.classList.remove('active');
                currentIndex++;

                if (currentIndex < questionCards.length) {
                    questionCards[currentIndex].classList.add('active');
                } else {
                    showResults();
                }
            }, 1000);
        });
    });
}

function checkAnswer(questionId, correctAnswer, userAnswer) {
    const messageBox = document.getElementById('message_' + questionId);

    if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
        messageBox.textContent = 'Correct Answer!';
        messageBox.classList.remove('incorrect');
        messageBox.classList.add('correct');
        score += 5;
        correctAnswers++;
    } else {
        messageBox.textContent = 'Wrong Answer!';
        messageBox.classList.remove('correct');
        messageBox.classList.add('incorrect');
    }
}

function sendAnswerToDatabase(answer, questionText, mark) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'insert_answer.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Answer inserted successfully');
        } else {
            console.error('Error inserting answer');
        }
    };

    xhr.send(`answer=${encodeURIComponent(answer)}&question_text=${encodeURIComponent(questionText)}&mark=${mark}`);
}

function showResults() {
    document.getElementById('resultBox').style.display = 'block';
    document.getElementById('finalScore').textContent = score;
    document.getElementById('correctCount').textContent = correctAnswers;
    document.getElementById('totalQuestions').textContent = totalQuestions;

    setTimeout(() => {
        window.location.href = "quiz.php";
    }, 5000);
}

window.onload = loadQuiz;

    </script>
</body>
</html>

