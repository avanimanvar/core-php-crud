<?php 
 
    $localhost = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "core_php_crud"; 
    
    // create connection 
    $conn = new mysqli($localhost, $username, $password, $dbname); 
    
    // check connection 
    if($conn->connect_error) {
        die("connection failed : " . $conn->connect_error);
    } else {
        //echo "Successfully Connected";
    }
 
?>