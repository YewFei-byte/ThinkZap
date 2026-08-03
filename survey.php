<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_SESSION['userName'];
    $answers = $_POST['answers'];
    
    foreach ($answers as $question_id => $answer) {
        if (is_array($answer)) {
            $answer = implode(',', $answer);
        }

        $answer = mysqli_real_escape_string($connection, $answer);

        $query = "SELECT question_text, question_type, options FROM survey_questions WHERE survey_id = '$question_id'";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $question_text = $row['question_text'];
            $insert_query = "INSERT INTO survey_answers (name, question_text, answer_text) 
                             VALUES ('$user_name', '$question_text', '$answer')";
            mysqli_query($connection, $insert_query);
        }
    }

    header("Location: homepage.php");

    exit;
}

$query = "SELECT * FROM survey_questions ORDER BY RAND()";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    echo "No survey questions available.";
    exit;
}

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey</title>
    <link rel="stylesheet" href="styleS.css">
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
        <a href="homepage.php">
            <img src="back_button.png" width="50px" alt="Return Logo">
        </a>
    </div>
    <div class="container">
        <form method="post" action="survey.php">
            <h1>Survey</h1>

            <div id="questions-container">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question batch hidden" data-batch="<?php echo floor($index / 5); ?>">
                        <label for="question_<?php echo $question['survey_id']; ?>">
                            <?php echo $question['question_text']; ?>
                        </label>

                        <?php if ($question['question_type'] == 'text'): ?>
                            <input type="text" name="answers[<?php echo $question['survey_id']; ?>]" required>
                        <?php elseif ($question['question_type'] == 'textarea'): ?>
                            <textarea name="answers[<?php echo $question['survey_id']; ?>]" required></textarea>
                        <?php elseif (in_array($question['question_type'], ['radio', 'checkbox', 'multiple-choice'])): ?>
                            <?php if (!empty($question['options'])): ?>
                                <?php $options = explode(',', $question['options']); ?>
                                <?php foreach ($options as $option): ?>
                                    <?php 
                                        $clean_option = str_replace(['[', ']', '"'], '', trim($option)); 
                                    ?>
                                    <label>
                                        <input type="<?php echo $question['question_type'] == 'radio' ? 'radio' : 'checkbox'; ?>" 
                                            name="answers[<?php echo $question['survey_id']; ?>]<?php echo $question['question_type'] == 'checkbox' ? '[]' : ''; ?>" 
                                            value="<?php echo htmlspecialchars($clean_option, ENT_QUOTES, 'UTF-8'); ?>" 
                                            <?php echo $question['question_type'] == 'radio' ? 'required' : ''; ?>>
                                        <?php echo htmlspecialchars($clean_option, ENT_QUOTES, 'UTF-8'); ?>
                                    </label><br>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button type="button" id="back-button">Back</button>
            <button type="button" id="next-button">Next</button>
            <button type="submit" id="submit-button">Submit</button>
        </form>
    </div>

    <script>
        const questions = document.querySelectorAll('.batch');
        const nextButton = document.getElementById('next-button');
        const backButton = document.getElementById('back-button');
        const submitButton = document.getElementById('submit-button');
        const form = document.querySelector('form');
        let currentBatch = 0;

        function showBatch(batch) {
            questions.forEach((question) => {
                question.classList.add('hidden');
                if (parseInt(question.dataset.batch) === batch) {
                    question.classList.remove('hidden');
                }
            });
            backButton.style.display = batch > 0 ? 'inline-block' : 'none';
            nextButton.style.display = batch < Math.floor(questions.length / 5) ? 'inline-block' : 'none';
            submitButton.style.display = batch === Math.floor(questions.length / 5) ? 'inline-block' : 'none';
        }

        function validateBatch(batch) {
            const batchQuestions = Array.from(questions).filter(
                (question) => parseInt(question.dataset.batch) === batch
            );

            for (let question of batchQuestions) {
                const inputs = question.querySelectorAll('input, textarea');
                let isValid = false;

                inputs.forEach((input) => {
                    if (
                        (input.type === 'radio' || input.type === 'checkbox') &&
                        input.checked
                    ) {
                        isValid = true;
                    } else if (
                        (input.type === 'text' || input.tagName === 'TEXTAREA') &&
                        input.value.trim() !== ''
                    ) {
                        isValid = true;
                    }
                });

                if (!isValid) {
                    return false;
                }
            }
            return true;
        }

        nextButton.addEventListener('click', () => {
            if (!validateBatch(currentBatch)) {
                alert('Please answer all the questions in the current section.');
                return;
            }

            currentBatch++;
            showBatch(currentBatch);
        });

        backButton.addEventListener('click', () => {
            currentBatch--;
            showBatch(currentBatch);
        });

        form.addEventListener('submit', (event) => {
            if (!validateBatch(currentBatch)) {
                event.preventDefault();
                alert('Please answer all the questions before submitting.');
                return;
            }else{
                alert('Thank you for completing the survey!');
            }
        });

        showBatch(currentBatch);
    </script>

    <footer class="about-us-footer">
        <div class="footer-content">
            <h2>About Us</h2>
            <table border="0">
                <tr>
                    <td>- <a href="CYQ.html">Chong Yin Quan</a></td>
                    <td>- <a href="YHC.html">Yong Hong Chang</a></td>
                    <td>- <a href="LYH.html">Lai Yik Hong</a></td>
                    <td>- <a href="LWH.html">Lim Wei Han</a></td>
                    <td>- <a href="LYF.html">Lam Yew Fei</a></td>
                </tr>
            </table>
        </div>
    </footer>
</body>
</html>
