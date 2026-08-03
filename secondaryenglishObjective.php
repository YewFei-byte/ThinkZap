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
    echo "Answer submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

$sql = "SELECT quiz_id, quiz_subject, quiz_question, quiz_option, correct_answer, quiz_level 
        FROM quiz_question 
        WHERE quiz_type = 'objective' AND quiz_subject = 'English' AND quiz_level = 'secondary'";

$result = $conn->query($sql);

$questions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options = explode(',', $row['quiz_option']);
        $options = array_map(function ($option) {
            return trim(str_replace(['[', ']', '"', "'"], '', $option));
        }, $options);

        if (!in_array($row['correct_answer'], $options)) {
            $options[] = $row['correct_answer'];
        }

        shuffle($options); 

        $row['quiz_option'] = $options;
        $questions[] = $row;

        echo "Question: " . $row['quiz_question'] . "<br>";
        echo "Options: " . implode(", ", $options) . "<br><br>";
    }
} else {
    echo "<p>The question is not found.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thinkzap Quiz</title>
    <link rel="stylesheet" href="styleSEO.css">
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
        <a href="Objective.php">
            <img src="back_button.png" alt="Return Logo">
        </a>
    </div>
    <div class="quiz-container">
    <?php foreach ($questions as $index => $question): ?>
        <div class="question-card <?= $index === 0 ? 'active' : ''; ?>" 
             data-correct="<?= htmlspecialchars($question['correct_answer']); ?>">
            <h2>Question <?= $index + 1; ?></h2>
            <h3><?= htmlspecialchars($question['quiz_question']); ?></h3>
            <div class="options">
                <?php foreach ($question['quiz_option'] as $option): ?>
                    <label>
                        <input type="radio" name="Q<?= $question['quiz_id']; ?>" value="<?= htmlspecialchars($option); ?>">
                        <?= htmlspecialchars($option); ?>
                    </label>
                <?php endforeach; ?>
            </div>
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

function loadQuiz() {
    const questionCards = document.querySelectorAll('.question-card');
    let currentIndex = 0;

    if (questionCards.length > 0) {
        questionCards[currentIndex].classList.add('active');
    }

    questionCards.forEach((card, index) => {
        const inputs = card.querySelectorAll('input[type="radio"]');

        inputs.forEach(input => {
            input.addEventListener('click', () => {
                inputs.forEach(input => input.disabled = true);

                const userAnswer = input.value;
                const correctAnswer = card.getAttribute('data-correct');
                let mark = 0;

                if (userAnswer === correctAnswer) {
                    score += 10;
                    correctAnswers++;
                    input.parentNode.style.backgroundColor = '#4CAF50';
                    mark = 10;
                } else {
                    input.parentNode.style.backgroundColor = '#f44336';
                    const correctOption = card.querySelector(`input[value="${correctAnswer}"]`);
                    if (correctOption) {
                        correctOption.parentNode.style.backgroundColor = '#4CAF50';
                    }
                }

                const questionText = card.querySelector('h3').textContent;
                insertAnswer(userAnswer, questionText, mark);

                setTimeout(() => {
                    card.classList.remove('active');
                    currentIndex++;

                    if (currentIndex < questionCards.length) {
                        questionCards[currentIndex].classList.add('active');
                    } else {
                        showResults(questionCards.length);
                    }
                }, 1000);
            });
        });
    });
}

function insertAnswer(answer, questionText, mark) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'insert_answer.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Answer inserted successfully');
        }
    };
    xhr.send(`answer=${answer}&question_text=${encodeURIComponent(questionText)}&mark=${mark}`);
}


function showResults(totalQuestions) {
    const resultBox = document.getElementById('resultBox');
    resultBox.style.display = 'block';
    document.getElementById('finalScore').textContent = score;
    document.getElementById('correctCount').textContent = correctAnswers;
    document.getElementById('totalQuestions').textContent = totalQuestions;

    setTimeout(() => {
        window.location.href = "quiz.php";
    }, 5000);
}

document.addEventListener('DOMContentLoaded', loadQuiz);

    </script>
    <footer class="about-us-footer">
        <div class="footer-content">
            <h2>About Us</h2>
            <table border="0">
                <tr>
                    <td>- <a href="CYQ.php">Chong Yin Quan</a></td>
                    <td>- <a href="YHC.php">Yong Hong Chang</a></td>
                    <td>- <a href="LYH.php">Lai Yik Hong</a></td>
                    <td>- <a href="LWH.php">Lim Wei Han</a></td>
                    <td>- <a href="LYF.php">Lam Yew Fei</a></td>
                </tr>
            </table>
        </div>
    </footer>
</body>
</html>
