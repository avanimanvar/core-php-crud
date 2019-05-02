<?php
require_once 'connection.php';


//update product
if ($_POST) {
    $pname = $_POST['pname'];

    $id = $_POST['id'];
    $sql = "UPDATE product SET product_name = '$pname' WHERE id = {$id}";
    if ($conn->query($sql) === TRUE) {
        $delete = 'DELETE FROM `product_category` WHERE product_id = ' . $id;
        $conn->query($delete);

        if (!empty($_POST['selected_cat'])) {
            foreach ($_POST['selected_cat'] as $key => $val) {
                $sql = "INSERT INTO product_category (product_id, category_id) VALUES ('$id', '$val')";
                $conn->query($sql);
            }
        }
        header('Location: http://localhost/core-php-crud/');
    } else {
        echo "Erorr while updating record : " . $connect->error;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT p.*, group_concat(tc.id) as catgory_ids FROM product p "
            . "LEFT JOIN product_category as pc ON pc.product_id = p.id "
            . "LEFT JOIN tab_cat as tc ON tc.id = pc.category_id "
            . "WHERE p.id = {$id}";
    $presult = $conn->query($sql);
    $data = $presult->fetch_assoc();
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <title>Edit Product</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
            <style type="text/css">
                fieldset {
                    margin: auto;
                    margin-top: 100px;
                    width: 50%;
                }

                table tr th {
                    padding-top: 20px;
                }
            </style>

        </head>
        <body>

            <fieldset>
                <legend>Edit Product</legend>
                <?php
                $sql = "SELECT * FROM tab_cat";
                $result = $conn->query($sql);
                $category = ['categories' => [], 'parent_cats' => []];

                //build the array lists with data from the category table
                while ($row = mysqli_fetch_assoc($result)) {
                    //creates entry into categories array with current category id ie. $categories['categories'][1]
                    $category['categories'][$row['id']] = $row;
                    //creates entry into parent_cats array. parent_cats array contains a list of all categories with children
                    $category['parent_cats'][$row['parent_id']][] = $row['id'];
                }

                function buildCategory($parent, $category, $display, $existing_cat)
                {
                    $html = "";
                    if (isset($category['parent_cats'][$parent])) {
                        $html .= "<ul style='list-style-type:none;display:" . $display . "'>\n";
                        foreach ($category['parent_cats'][$parent] as $cat_id) {
                            $isChecked = '';
                            $display = 'none';
                            if (in_array($category['categories'][$cat_id]['id'], $existing_cat)) {
                                $isChecked = 'checked';
                                $display = 'block';
                            }
                            if (!isset($category['parent_cats'][$cat_id])) {
                                $html .= "<li>\n  <input type='checkbox' name='selected_cat[]' class='categories' " . $isChecked . " value='" . $category['categories'][$cat_id]['id'] . "'><span>" . $category['categories'][$cat_id]['cat_name'] . "</span>\n</li> \n";
                            }
                            if (isset($category['parent_cats'][$cat_id])) {
                                $html .= "<li>\n  <input type='checkbox' name='selected_cat[]' class='parent_category' " . $isChecked . " value='" . $category['categories'][$cat_id]['id'] . "'/><span>" . $category['categories'][$cat_id]['cat_name'] . "</span> \n";
                                $html .= buildCategory($cat_id, $category, $display, $existing_cat);
                                $html .= "</li> \n";
                            }
                        }
                        $html .= "</ul> \n";
                    }
                    return $html;
                }

                $existing_cat = !empty($data['catgory_ids']) ? explode(',', $data['catgory_ids']) : [];
                ?>
                <form action="edit.php" method="post">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td>Product Name</td>
                            <td><input type="text" name="pname" placeholder="First Name" value="<?php echo $data['product_name'] ?>" /></td>
                        </tr>     
                        <tr>
                            <td>Category Name</td>
                            <td>
                                <input type="button" value="Check All" onclick="checkAll();" style="margin-top:20px;">
                                <input type="button" value="Un Check All" onclick="unCheckAll();" style="margin-top:20px;">
                                <?php echo buildCategory(0, $category, 'block', $existing_cat); ?>
                            </td>
                        </tr>
                        <tr>
                        <input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
                        <td><button type="submit">Save Changes</button></td>
                        <td><a href="index.php"><button type="button">Back</button></a></td>
                        </tr>
                    </table>
                </form>

            </fieldset>

        </body>
    </html>

    <?php
}
?>


<script type="text/javascript">
    $(document).ready(function () {
        $('.parent_category').on('click', function () {
//            console.log($(this).prop('checked'));
            if ($(this).prop('checked')) {
                $(this).parent().children('ul').show();
            } else {
                $(this).parent().children('ul').find('input:checkbox').prop('checked', false)
                $(this).parent().children('ul').hide();
            }
        });
    });
    function checkAll() {
        $('input[name="selected_cat[]"]').prop('checked', true);
        $('ul').show();
    }
    function unCheckAll() {
        $('input[name="selected_cat[]"]').prop('checked', false);
        $('ul:not(:first)').hide();
    }
</script>