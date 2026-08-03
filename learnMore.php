<?php
session_start();
if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn More - ThinkZap</title>
    <link rel="stylesheet" href="styleHP.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <div class="img">
                    <a href="homePage.php"></a>
                    <img src="ThinkZapLogo.png" width="400px" alt="ThinkZap Logo">
                </div>
            </div>
            <p>The Ultimate Learning and Assessment Platform</p>
        </header>
        <div class="content">
            <h2>About ThinkZap</h2>
            <p>The aim of creating this website is to assist students in their studies. The user just needs to register their email
                , name, and password to log in to their account on the website. To accommodate various learning methods and subject 
                matters, the web program will provide skill and knowledge assessment, such as knowledge tests, skill assessments, 
                and quizzes. The questions provided are categorized into multiple choice questions, subjective questions, and HOTS 
                (Higher Order Thinking Skills) questions.Not only that, but the website also allows the user to create and customize 
                the quizzes and tests tailored to their likings. Using our website provides users easier customization and adjustments 
                to their quizzes, regardless of their knowledge on the internet, just by stating the questions and correct answers 
                according to the questions given. Our website can also be used as a multi-use platform for not only creating quizzes 
                but also creating surveys or questionnaires. By choosing the type of document, the system will adjust according to 
                the user's choice. For example, if the user chooses to make a survey, the survey will not have a right or wrong answer, 
                vice versa. The website also contains an Online Leaderboard system where users can view others' scores for every 
                specific subject, so the user will know what their level is in their studies. Before attempting the questions, students 
                can revise what they have learned during their lessons to have a better understanding or recall back what they have 
                learned, as the website will provide simplified learning materials. For example, it will generate some of the questions 
                and their answers to let the user know how to solve the question when facing it. The website encourages users to develop 
                a fast-thinking mindset when confronted with challenging problems to help them stay composed and avoid panicking. By 
                training users to handle HOTS questions in tests, a timer will start when the user begins answering the question, 
                allowing them to approach these questions with clarity and focus. The timer can be customized by the user. This system 
                helps them organize their time when taking exams and reduces anxiety.Once the user finishes answering the questions, 
                the website will provide prompt results and thorough feedback on assessments to assist users in understanding their 
                performance and making necessary corrections.</p>

            <div class="cta-buttons">
                <a href="quiz.php" class="button">Get Started Now</a>
            </div>
        </div>
        <footer class="about-us-footer">
            <div class="footer-content">
                <h2>About Us</h2>
                <table>
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
    </div>
</body>
</html>
