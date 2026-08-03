<?php
    //step1 -create a connection to your database
    //procedural - mysqli
    $hostname = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'rwdd2307';
    $connection= mysqli_connect($hostname,$user,$password,$database);
    // SQL query to fetch data from the 'users' table
    $sql = "SELECT id, name, email FROM users";
    $result = $connection->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Create an array to store the results
        $users = array();
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        // Output data in JSON format
        echo json_encode($users);
    } else {
        echo json_encode([]);
    }

    // Close connection
    $conn->close();
        // } else{
        //     echo"Connection Successful";
        // }
?>