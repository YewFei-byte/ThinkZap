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

$sql_objective = "SELECT quiz_id, quiz_subject, quiz_question, quiz_option, correct_answer, quiz_level 
                  FROM quiz_question 
                  WHERE quiz_type = 'objective' AND quiz_subject = 'Math' AND quiz_level = 'secondary'";
$result_objective = $conn->query($sql_objective);

$objective_questions = [];
if ($result_objective->num_rows > 0) {
    while ($row = $result_objective->fetch_assoc()) {
        $options = explode(',', $row['quiz_option']);
        $options = array_map(function ($option) {
            return trim(str_replace(['[', ']', '"', "'"], '', $option));
        }, $options);
        if (!in_array($row['correct_answer'], $options)) {
            $options[] = $row['correct_answer'];
        }
        shuffle($options);
        $row['quiz_option'] = $options;
        $objective_questions[] = $row;
    }
} else {
    echo "<p>The question is not found.</p>";
}

$sql_subjective = "SELECT quiz_id, quiz_subject, quiz_question, correct_answer 
                  FROM quiz_question 
                  WHERE quiz_type = 'subjective' AND quiz_subject = 'Math' AND quiz_level = 'secondary'";
$result_subjective = $conn->query($sql_subjective);

$subjective_questions = [];
if ($result_subjective->num_rows > 0) {
    while ($row = $result_subjective->fetch_assoc()) {
        $subjective_questions[] = $row;
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
    <link rel="stylesheet" href="styleSMM.css">
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
<div class="quiz-container">
    <?php foreach ($objective_questions as $index => $question): ?>
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

    <?php foreach ($subjective_questions as $index => $question): ?>
        <div class="question-card" 
             data-name="Q<?= $question['quiz_id']; ?>" 
             data-correct="<?= htmlspecialchars($question['correct_answer']); ?>">
            <h2>Question <?= count($objective_questions) + $index + 1; ?></h2>
            <h3><?= htmlspecialchars($question['quiz_question']); ?></h3>
            <textarea id="answer_<?= $question['quiz_id']; ?>" placeholder="Type your answer here..."></textarea>
            <button>Confirm</button>
        </div>
    <?php endforeach; ?>

    <div class="result-box" id="resultBox" style="display: none;">
        <h2>Quiz Completed!</h2>
        <p>Your score: <span id="finalScore">0</span></p>
        <p>You got <span id="correctCount">0</span> correct out of <span id="totalQuestions">0</span> questions.</p>
    </div>
</div>

        <script>

document.addEventListener('DOMContentLoaded', function () {
    let score = 0;
    let correctAnswers = 0;
    let currentIndex = 0;
    const questionCards = document.querySelectorAll('.question-card');
    const totalQuestions = questionCards.length;

    if (questionCards.length > 0) {
        questionCards[currentIndex].classList.add('active');
    }

    function loadQuiz() {
        questionCards.forEach((card, index) => {
            const inputs = card.querySelectorAll('input[type="radio"]');
            const textarea = card.querySelector('textarea');
            const confirmBtn = card.querySelector('button');

            if (inputs.length > 0) {
                inputs.forEach(input => {
                    input.addEventListener('click', () => {
                        processAnswer(input, card, index);
                    });
                });
            }

            if (textarea && confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    processAnswer(textarea, card, index);
                });
            }
        });
    }

    function processAnswer(selectedInput, card, index) {
    const correctAnswer = card.getAttribute('data-correct');
    let selectedValue = "";

    if (selectedInput.tagName === "TEXTAREA") {
        selectedValue = selectedInput.value.trim();
    } else {
        selectedValue = selectedInput.value;
    }

    const isCorrect = selectedValue.toLowerCase() === correctAnswer.toLowerCase();
    const isSubjective = selectedInput.tagName === "TEXTAREA";

    if (isCorrect) {
        score += isSubjective ? 5 : 2;
        correctAnswers++;
    }

    const questionText = card.querySelector('h3').textContent;
    const mark = isSubjective ? (isCorrect ? 5 : 0) : (isCorrect ? 2 : 0);
    sendAnswerToDatabase(selectedValue, questionText, mark);

    const inputs = card.querySelectorAll('input, textarea');
    inputs.forEach(input => input.disabled = true);

    if (isCorrect) {
        selectedInput.parentNode.style.backgroundColor = '#4CAF50';
    } else {
        selectedInput.parentNode.style.backgroundColor = '#f44336';
        if (!isSubjective) {
            const correctOption = card.querySelector(`input[value="${correctAnswer}"]`);
            if (correctOption) {
                correctOption.parentNode.style.backgroundColor = '#4CAF50';
            }
        }
    }


    setTimeout(() => {
        card.classList.remove('active');
        currentIndex++;

        if (currentIndex < totalQuestions) {
            questionCards[currentIndex].classList.add('active');
        } else {
            showResults();
        }
    }, 1000);
}


    function showResults() {
        const resultBox = document.getElementById('resultBox');
        resultBox.style.display = 'block';
        document.getElementById('finalScore').textContent = score;
        document.getElementById('correctCount').textContent = correctAnswers;
        document.getElementById('totalQuestions').textContent = totalQuestions;

        setTimeout(() => {
            window.location.href = 'quiz.php';
        }, 5000);
    }

    function sendAnswerToDatabase(answer, questionText, mark) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'insert_answer.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Answer inserted successfully:', xhr.responseText);
            } else {
                console.error('Error inserting answer:', xhr.responseText);
            }
        };

        xhr.send(`answer=${encodeURIComponent(answer)}&question_text=${encodeURIComponent(questionText)}&mark=${mark}`);
    }

    loadQuiz();
});

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