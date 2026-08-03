<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['adminName'];

$query = "SELECT * FROM quiz_question ORDER BY quiz_id ASC";
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
    <title>Quiz Management</title>
    <link rel="stylesheet" href="Table.css">
</head>
<body>
    <img src="logo.png" alt="Logo" class="logo">
    <div class="back-container">
        <a href="adminPage.php">
            <img src="back.png" alt="Back" class="back-btn">
        </a>
    </div>

    <div class="container">
        <h2>Quiz Questions Management</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Type</th>
                <th>Options</th>
                <th>Correct Answer</th>
                <th>Level</th>
                <th>Subject</th>
                <th colspan="2">Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['quiz_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['quiz_question']); ?></td>
                    <td><?php echo htmlspecialchars($row['quiz_type']); ?></td>
                    <td>
                        <?php
                        if (!empty($row['quiz_option'])) {
                            $options = json_decode($row['quiz_option'], true);
                            echo implode(', ', $options);
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['correct_answer']); ?></td>
                    <td><?php echo htmlspecialchars($row['quiz_level']); ?></td>
                    <td><?php echo htmlspecialchars($row['quiz_subject']); ?></td>
                    <td><a href="EditQuiz.php?quiz_id=<?php echo $row['quiz_id']; ?>">Edit</a></td>
                    <td><a href="DeleteQuiz.php?quiz_id=<?php echo $row['quiz_id']; ?>" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
<?php
mysqli_close($connection);
?>