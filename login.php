<?php
session_start();
include 'connection.php';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $email_query = "SELECT * FROM tbluser WHERE email = '$email'";
    $email_result = mysqli_query($connection, $email_query);

    if (mysqli_num_rows($email_result) > 0) {
        $row = mysqli_fetch_assoc($email_result);
        if ($row['password'] === $password) {
            if ($row['role'] == 'admin') {
                $_SESSION['adminName'] = $row['firstname'] . ' ' . $row['lastname'];
                header('Location: Welcome.php');
                exit;
            } elseif ($row['role'] == 'user') {
                $_SESSION['userName'] = $row['firstname'] . ' ' . $row['lastname'];
                header('Location: homePage.php');
                exit;
            }
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('User does not exist. Please register first.');</script>";
    }
}

mysqli_close($connection);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styleLR.css">
</head>
<body>
    <div class="header">
    <img src="ThinkZapLogo.png" alt="Logo" class="img">
    </div>
    <div class="container">
        <form action="" method="post">
            <h2>Log In</h2>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" name="submit" value="Login" class="loginBtn">
            <p>Don't have an account? <a href="register.php">Register</a></p>
            <p><a href="forgetPassword.php">Forget Password?</a></p>
        </form>
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
</html>
