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
    <title>Document</title>
</head>
<style>
    .image{
    width: 200px;
    margin-right: 20px;
    }
    .picture{
    text-align: center;
    }
    body {  
    font-family: Arial, sans-serif;
    background-color:#fff9c4;
    }
</style>
<body>
<a href="javascript:history.back()" class="back-button">
            <img src="back_button.png" alt="Back Button" width="40px" height="40px">
        </a>
    <h1>About Chong Yin Quan</h1>
    <div class="picture">
        <img src="LYF.jpg" alt="" class="image">
    </div>
    <h2><center>yewfeilam@gmail.com</center></h2>
    <h2><u>Description</u></h2>
    <p>The designer of About us, account details and home page</p>
</body>
</html>