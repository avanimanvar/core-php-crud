<?php 
 
require_once 'connection.php';
 
if(isset($_GET['id'])) {
    $id = $_GET['id'];
 
    $sql = "DELETE FROM product WHERE id = {$id}";
    if($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error updating record : " . $conn->error;
    }
 
    $conn->close();
}
 
?>