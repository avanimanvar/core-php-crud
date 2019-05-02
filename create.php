<!DOCTYPE html>
<html>
    <head>
        <title>Add Product</title>
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
    <?php
    require_once 'connection.php';
    if ($_POST) {
        $pname = $_POST['pname'];
        $sql = "INSERT INTO product (product_name) VALUES ('$pname')";
        if ($conn->query($sql) === TRUE) {
            $product_id = $conn->insert_id;
            if (!empty($_POST['selected_cat'])) {
                foreach ($_POST['selected_cat'] as $key => $val) {
                    $sql = "INSERT INTO product_category (product_id, category_id) VALUES ('$product_id', '$val')";
                    $conn->query($sql);
                }
            }
            header('Location: http://localhost/core-php-crud/');
        } else {
            echo "Error " . $sql . ' ' . $conn->connect_error;
        }
    }
    ?>
    <body>
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

        function buildCategory($parent, $category, $display)
        {
            $html = "";
            if (isset($category['parent_cats'][$parent])) {
                $html .= "<ul style='list-style-type:none;display:" . $display . "'>\n";
                foreach ($category['parent_cats'][$parent] as $cat_id) {
                    if (!isset($category['parent_cats'][$cat_id])) {
                        $html .= "<li>\n  <input type='checkbox' name='selected_cat[]' class='categories' value='" . $category['categories'][$cat_id]['id'] . "'><span>" . $category['categories'][$cat_id]['cat_name'] . "</span>\n</li> \n";
                    }
                    if (isset($category['parent_cats'][$cat_id])) {
                        $html .= "<li>\n  <input type='checkbox' name='selected_cat[]' class='parent_category' value='" . $category['categories'][$cat_id]['id'] . "'/><span>" . $category['categories'][$cat_id]['cat_name'] . "</span> \n";
                        $html .= buildCategory($cat_id, $category, 'none');
                        $html .= "</li> \n";
                    }
                }
                $html .= "</ul> \n";
            }
            return $html;
        }
        ?>
        <fieldset>
            <legend>Add Product</legend>

            <form action="create.php" method="post">
                <table cellspacing="0" cellpadding="0">
                    <tr>
                        <td>Product Name</td>
                        <td><input type="text" name="pname" placeholder="Product Name" /></td>
                    </tr>     
                    <tr>
                        <td>Category Name</td>
                        <td>
                            <input type="button" value="Check All" onclick="checkAll();" style="margin-top:20px;">
                            <input type="button" value="Un Check All" onclick="unCheckAll();" style="margin-top:20px;">
                            <?php echo buildCategory(0, $category, 'block'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><button type="submit">Save Changes</button></td>
                        <td><a href="index.php"><button type="button">Back</button></a></td>
                    </tr>
                </table>
            </form>

        </fieldset>

    </body>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        $('.parent_category').on('click', function () {
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