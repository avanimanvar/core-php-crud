<?php
    include('connection.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP CRUD</title>
 
    <style type="text/css">
        .manageMember {
            width: 50%;
            margin: auto;
        }
 
        table {
            width: 100%;
            margin-top: 20px;
        }
 
    </style>
 
</head>
<body>
 
<div class="manageMember">
    <a href="create.php"><button type="button">Add Product</button></a>
    <?php
        $sql = "SELECT p.*,group_concat(tc.cat_name) as cat_name FROM product as p "
                . "LEFT JOIN product_category as pc ON pc.product_id = p.id "
                . "LEFT JOIN tab_cat as tc ON tc.id = pc.category_id group by pc.product_id";
        $result = $conn->query($sql);
//        session_start();
//        $_SESSION['path']='';
//        function getPath($id,$con){
//            if($id != '0'){
//                $gsql = "SELECT * FROM tab_cat WHERE id = '".$id."'"; 
//                $gresult = $con->query($gsql);
//                //print_r($gresult);exit;
//                while($grow = $gresult->fetch_assoc()) {
//                    $_SESSION['path'] .= $grow['parent_id'].',';echo  $grow['parent_id'];
//                    getpath($grow['parent_id'],$con);
//                }
//            }
//        }
    ?>
    <table border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {?>
                    <tr>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['cat_name']; 
//                        $path[]=$row['cat_id'];
//                            getPath($row['cat_id'],$conn);
//                            $path[$row['id']] = $row['cat_id'];
//                            
                            ?>
                                                        
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id'];?>"><button type='button'>Edit</button></a>
                            <a href="remove.php?id=<?php echo $row['id'];?>"><button type='button'>Delete</button></a>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
 
</body>
</html>
