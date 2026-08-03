<?php
    //step1 -create a connection to your database
    //procedural - mysqli
    $hostname = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'rwdd2307';
   $connection= mysqli_connect($hostname,$user,$password,$database);

    if($connection === false){
        die("Connection Failed".mysqli_connect_error());
    }
    // } else{
    //     echo"Connection Successful";
    // }
?>