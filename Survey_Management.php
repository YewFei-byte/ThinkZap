<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['adminName'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['adminName'];

$query = "SELECT * FROM survey_questions ORDER BY survey_id ASC";
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
    <title>Survey Questions</title>
    <link rel="stylesheet" href="Table.css">
</head>
<body>
<div class="header">
    <div style="display: flex; align-items: center;">
    <img src="logo.png" alt="Logo" class="logo">
</div>
    <div class="back-container">
        <a href="adminPage.php">
            <img src="back.png" alt="Back" class="back-btn">
        </a>
    </div>
    
    <div class="container">
        <h2>Survey Questions Management</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Question Text</th>
                <th>Question Type</th>
                <th>Options</th>
                <th colspan="2">Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['survey_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                    <td><?php echo htmlspecialchars($row['question_type']); ?></td>
                    <td>
                        <?php
                        if (!empty($row['options'])) {
                            $options = json_decode($row['options'], true);
                            echo implode(', ', $options);
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    <div class="buttons">
                    <td><a href="EditSurvey.php?id=<?php echo $row['survey_id']; ?>">Edit</a></td>
                    <td><a href="DeleteSurvey.php?id=<?php echo $row['survey_id']; ?>" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a></td>
                    </div>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
<?php
mysqli_close($connection);
?>
