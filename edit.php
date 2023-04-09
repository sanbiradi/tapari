<?php
include_once "controller/header.php";
include_once "controller/db.php";
include_once "controller/session.php";
@$target_file = @$title = @$description = @$price = '';
@$file_err = @$title_err = @$desc_err = @$price_err = '';

//form submited
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //catch the value with filters
    $id = filter_var($_GET['k'], FILTER_SANITIZE_STRING);
    $id = base64_decode($id);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $target_file = $_FILES["image"]["name"];
    $noerrors = true;
    $file_name = '';

    //validations of the values
    if (empty($title)) {
        $title_err = 'Enter the title!';
        $noerrors = 0;
    }

    if (empty($description)) {
        $desc_err = 'Enter the description!';
        $noerrors = 0;
    }

    if (empty($price)) {
        $price_err = 'Enter the price!';
        $noerrors = 0;
    }

    //check the errors then upload a image
    if (empty($target_file)) {
    } else if (isset($_FILES['image'])) {
        $target_file = basename($_FILES["image"]["name"]);
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name']; //temparary directory of the server use
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions = array("jpeg", "jpg", "png");

        //check the extensions
        if (in_array($file_type, $extensions) === false) {
            $file_err = "extension not allowed, please choose a JPEG or PNG file!";
        }

        //check the file size
        if ($file_size > 2097152) {
            $file_err = 'File size must be excately 2 MB!';
        }

        //check the errors is empty
        if (empty($file_err) == true) {
            $file_name = $file_tmp;
        }
    }

    //if there is no error then store the values in the database.

    if ($noerrors) {
        if (empty($target_file)) {
            $query = "UPDATE products SET title='$title',description='$description',price='$price' Where id='$id'";
        } else {
            $file_name = addslashes(file_get_contents($file_name));
            $query = "UPDATE products SET title='$title',description='$description',price='$price',image='$file_name' Where id='$id'";
        }
        $sql = $conn->query($query);
        if ($sql == 1) {
            $_SESSION['alert'] = '<div class="alert alert-success">Product is updated successfully!</div>';
            header('location:myProducts');
            die();
        }
    }
}
?>
<div class="container">
    <style>
        input[type="text"] {
            border-color: transparent;
            border-bottom: 1px solid #ccc;
            outline: none;
            width: 100%;
        }
        
    </style>
    <div class="col-md-8 col-md-offset-2">
        <h3 class="text-xs m-2"> <img src="images/tapari.png" alt="tapari" height="40"> Tapari
            <?php if (isset($_SESSION['user_key'])) { ?>
                <div class="dropdown pull-right mt-2">
                    <button class="btn btn-default dropdown-toggle" style="text-transform: capitalize;" type="button" data-toggle="dropdown">
                        <?php
                        $k = $_SESSION['user_key'];
                        $sql = $conn->query("SELECT * FROM users WHERE user_key='$k'");
                        while ($row = $sql->fetch_assoc())
                            echo $row['name'];
                        ?>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="profile">Profile</a></li>
                        <li><a href="myProducts">My products</a></li>
                        <!-- <li><a href="settings.php">Settings</a></li> -->
                        <li><a href="login?lg=<?php echo base64_encode('logout'); ?>">Logout</a></li>
                    </ul>
                </div>
                <a href="index.php" class="pull-right m-3">Home</a>
            <?php } ?>
        </h3>
        <hr><br><br>
        <div class="col-md-7 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Product Information
                </div>
                <?php
                $id = filter_var($_GET['k'], FILTER_SANITIZE_STRING);
                $id = base64_decode($id);
                if (isset($id)) {
                    $sql = $conn->query("SELECT*FROM products WHERE id='$id'");
                    $row = $sql->fetch_array();
                ?>
                    <div class="panel-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" name="title" value="<?php echo $row['title'] ?>">
                                <p class="error"><?php echo $title_err; ?></p>
                            </div>
                            <div class="form-group">
                                <input type="text" name="description" value="<?php echo $row['description'] ?>">
                                <p class="error"><?php echo $desc_err; ?></p>
                            </div>
                            <div class="form-group">
                                <input type="text" name="price" value="<?php echo $row['price'] ?>">
                                <p class="error"><?php echo $price_err; ?></p>
                            </div>
                            <?php $_SESSION['main_image'] = $row['image']; ?>
                            <div class="form-group">
                                <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['image']); ?>" alt="" height="100px" style="transform:translateX(100%);">
                                <br><br><label for="image">Upload New Image*</label>
                                <input type="file" name="image" id="">
                                <p class="error"><?php echo $file_err; ?></p>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="view?k=<?php echo base64_encode($row['id']); ?>" class="btn btn-default">Cancel</a>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php include_once "controller/footer.php"; ?>