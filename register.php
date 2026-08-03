<?php
include 'connection.php';

if (isset($_POST['submit'])) {
    $fname = mysqli_real_escape_string($connection, $_POST['txtFName']);
    $lname = mysqli_real_escape_string($connection, $_POST['txtLName']);
    $email = mysqli_real_escape_string($connection, $_POST['txtEmail']);
    $password = mysqli_real_escape_string($connection, $_POST['txtPassword']);
    $cpassword = mysqli_real_escape_string($connection, $_POST['txtCPassword']);
    $role = $_POST['selRole'];

    $email_query = "SELECT * FROM tbluser WHERE email = '$email'";
    $email_result = mysqli_query($connection, $email_query);

    $name_query = "SELECT * FROM tbluser WHERE firstname = '$fname' AND lastname = '$lname'";
    $name_result = mysqli_query($connection, $name_query);

    if (mysqli_num_rows($email_result) > 0) {
        echo "<script>alert('User with this email already exists');</script>";
    } elseif (mysqli_num_rows($name_result) > 0) {
        echo "<script>alert('A user with this name already exists');</script>";
    } else {
        if ($password !== $cpassword) {
            echo "<script>alert('Passwords do not match');</script>";
        } else {
            $query = "INSERT INTO tbluser (firstname, lastname, email, password, role) 
                      VALUES ('$fname', '$lname', '$email', '$password', '$role')";
            if (mysqli_query($connection, $query)) {
                echo "<script>alert('Registration successful! Redirecting to login...');</script>";
                header('Location: login.php');
                exit;
            } else {
                echo "<script>alert('Failed to register user');</script>";
            }
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
    <title>Register</title>
    <link rel="stylesheet" href="styleLR.css">
</head>
<body>
    <div class="header">
        <img src="ThinkZapLogo.png" alt="Logo" class="img">
    </div>
    <div class="container">
        <form action="" method="post">
            <h2>Register</h2>
            <input type="text" name="txtFName" placeholder="Enter your first name" required>
            <input type="text" name="txtLName" placeholder="Enter your last name" required>
            <input type="email" name="txtEmail" placeholder="Enter your email" required>
            <input type="password" name="txtPassword" placeholder="Enter your password" required>
            <input type="password" name="txtCPassword" placeholder="Confirm your password" required>
            <select name="selRole" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <input type="submit" name="submit" value="Register" class="registerBtn">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>

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
