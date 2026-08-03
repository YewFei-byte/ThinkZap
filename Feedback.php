<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['adminName'];

$query = "SELECT answer_id, name, question_text, answer_text FROM survey_answers ORDER BY question_text ASC";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="Table.css">
</head>
<body>
    <img src="logo.png" alt="Logo" class="logo">

    <div class="container">
        <h2>User Feedback</h2>
        <table class="feedback-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Question</th>
                    <th>Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['question_text']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['answer_text']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="back-container">
        <a href="adminPage.php">
            <img src="back.png" alt="Back" class="back-btn">
        </a>
    </div>
</body>
</html>

<?php
mysqli_close($connection);
?>