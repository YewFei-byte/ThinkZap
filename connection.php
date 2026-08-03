<?php
    //step1 -create a connection to your database
    //procedural - mysqli
    $hostname = 'sql306.infinityfree.com';  // from their panel
    $user = 'if0_42566626';
    $password = 'ICUgyTpYulKRV';
    $database = 'if0_42566626_db_name';
   $connection= mysqli_connect($hostname,$user,$password,$database);

    if($connection === false){
        die("Connection Failed".mysqli_connect_error());
    }
    // } else{
    //     echo"Connection Successful";
    // }
?>
