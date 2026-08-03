<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit;
}

mysqli_close($connection);
?>

<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "rwdd2307";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $levelOfEducation = $_POST['Education'] ?? '';
    $subject = $_POST['Subject'] ?? '';
    $questionType = $_POST['question_type'] ?? '';

    if (!empty($levelOfEducation) && !empty($subject)&& !empty($questionType)) {
        $sql = "INSERT INTO tblquestiontype (question_type, LevelOfEducation, subjects) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss",$questionType, $levelOfEducation, $subject);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Data saved successfully!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $connection->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Please select both level of education and subject."]);
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thinkzap</title>
    <link rel="stylesheet" href="styleO.css">
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
        <a href="quiz.php">
            <img src="back_button.png" alt="Return Logo">
        </a>
    </div>

    <div class="form-container">
        <h2>Objective question</h2>
        <form id="questionForm">
            <h3>Your level of education?</h3>  
            <label>
                <input type="radio" name="Education" value="Primary"> Primary
            </label>
            <label>
                <input type="radio" name="Education" value="Secondary"> Secondary
            </label>

            <h3>Choose your subjects</h3>  
            <label>
                <input type="radio" name="Subject" value="English"> English
            </label>
            <label>
                <input type="radio" name="Subject" value="Mathematics"> Mathematics
            </label>

            <button type="button" onclick="submitForm()">Submit</button>
        </form>

        <script>
        function submitForm() {
            const form = document.getElementById('questionForm');
            const formData = new FormData(form);

            formData.append("question_type", "Objective");

            
            fetch('Objective.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    
                    const subject = formData.get("Subject");
                    const levelOfEducation = formData.get("Education");
                    if (levelOfEducation === "Primary" && subject === "English") {
                        window.location.href = "englishObjective.php";
                    } else if (levelOfEducation === "Primary"&&subject === "Mathematics") {
                        window.location.href = "mathObjective.php";
                    } else if(levelOfEducation === "Secondary"&&subject === "English"){
                        window.location.href = "secondaryenglishObjective.php";
                    } else if(levelOfEducation === "Secondary"&&subject === "Mathematics"){
                        window.location.href = "secondarymathObjective.php";
                    }
                } else {
                    alert(data.message); 
                }
            })
            .catch(error => {
                alert("An error occurred: " + error.message);
            });
        }
        </script>
    </div>
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
