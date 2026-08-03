<?php
session_start();
include 'connection.php';

if (isset($_POST['submit'])) {
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        $query = "SELECT * FROM tbluser WHERE email = '$email'";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['email'] = $email;
            $message = "Email found! Please enter your new password below.";
        } else {
            $message = "Email not found. Please check your email.";
        }
    }
    elseif (isset($_POST['password']) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $newPassword = mysqli_real_escape_string($connection, $_POST['password']);
        $confirmPassword = mysqli_real_escape_string($connection, $_POST['cpassword']);

        if ($newPassword == $confirmPassword) {
            $query = "UPDATE tbluser SET password = '$newPassword' WHERE email = '$email'";
            if (mysqli_query($connection, $query)) {
                $_SESSION['success'] = "Your password has been successfully updated.";
                header('Location: login.php');
                exit();
            } else {
                $message = "Something went wrong. Please try again.";
            }
        } else {
            $message = "Passwords do not match.";
        }
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
<h1>Think Zap</h1>
</div>
    <div class="container">
        <form action="" method="post">
            <h2>Forget Password</h2>
            <?php if (!isset($_SESSION['email'])): ?>
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="submit" name="submit" value="Submit">
            <?php else: ?>
                <input type="password" name="password" placeholder="Enter new password" required>
                <input type="password" name="cpassword" placeholder="Confirm new password" required>
                <input type="submit" name="submit" value="Reset Password" class="resetBtn">
            <?php endif; ?>
            <p><a href="login.php">Back to Login</a></p>
        </form>
    </div>
</body>
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
