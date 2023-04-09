<?php
include_once "controller/header.php";
include_once "controller/db.php";
include "controller/session.php";
//global variables
@$target_file = @$title = @$description = @$price = '';
@$file_err = @$title_err = @$desc_err = @$price_err = '';

//form submited
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //catch the value with filters
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $target_file = $_FILES["image"]["name"];

    $file_name = '';
    $noerrors = true;

    //validations of the values
    if (empty($title)) {
        $title_err = 'Enter the title!';
        $noerrors = false;
    }

    if (empty($description)) {
        $desc_err = 'Enter the description!';
        $noerrors = false;
    }

    if (empty($price)) {
        $price_err = 'Enter the price!';
        $noerrors = false;
    }

    //check the errors then upload a image
    if (empty($target_file)) {
        $file_err = "Choose your file!";
        $noerrors = false;
    } else if (isset($_FILES['image'])) {
        $target_file = basename($_FILES["image"]["name"]);
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name']; //temparary directory of the server use
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions = array("jpeg", "jpg", "png");

        //check the extensions
        if (in_array($file_type, $extensions) === false) {
            $file_err = "extension not allowed, please choose a JPEG or PNG file!";
            $noerrors = false;
        }

        //check the file size
        if ($file_size > 2097152) {
            $file_err = 'File size must be excately 2 MB!';
            $noerrors = false;
        }

        //check the errors is empty
        if (empty($file_err) == true) {
            $file_name = $file_tmp;
        }
    }

    //if there is no error then store the values in the database.
    if ($noerrors) {
        $file_name = addslashes(file_get_contents($file_name));
        $product_key = uniqid();
        $user_key = $_SESSION['user_key'];
        $query = "INSERT INTO products(product_key,title,description,price,image) VALUES('$product_key','$title','$description','$price','$file_name');";
        // $stmt = $conn->prepare($query);
        // //bind the values
        // $stmt->bind_param('sssslb',$uniq, $title, $description, $price, $file_name);
        if ($conn->query($query) == true) {
            $query = "INSERT INTO user_products(user_key,product_key) VALUES('$user_key','$product_key')";
            if ($conn->query($query) == true) {
                $_SESSION['alert'] = '<div class="alert alert-success">Product is added successfully!</div>';
                header('location:myProducts');
                die();
            } else {
                $_SESSION['alert'] = '<div class="alert alert-danger">Something went wrong, Try again later.</div>';
            }
        } else {
            $_SESSION['alert'] = '<div class="alert alert-danger">Something went wrong, Try again later.</div>';
        }
        //set the value of those variables
    }
}
?>
<style>
    .error {
        color: red;
    }
</style>
<div class="container">
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
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="myProducts.php">My products</a></li>
                        <!-- <li><a href="settings.php">Settings</a></li> -->
                        <li><a href="login?lg=<?php echo base64_encode('logout'); ?>">Logout</a></li>
                    </ul>
                </div>
                <a href="index.php" class="pull-right m-3">Home</a>
            <?php } ?>
        </h3>
        <hr>
        <div class="col-md-7 col-md-offset-3">
            <div class="panel panel-default mt-5">
                <div class="panel-heading">
                    Add Product
                </div>
                <div class="panel-body">
                    <?php if (isset($_SESSION['alert'])) {
                        echo $_SESSION['alert'];
                        unset($_SESSION['alert']);
                    } ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" name="title" placeholder="Product title" class="form-control">
                            <p class="error"><?php echo $title_err; ?></p>
                        </div>
                        <div class="form-group">
                            <input type="text" name="description" placeholder="Product description" class="form-control">
                            <p class="error"><?php echo $desc_err; ?></p>
                        </div>
                        <div class="form-group">
                            <input type="text" name="price" placeholder="Product price" class="form-control">
                            <p class="error"><?php echo $price_err; ?></p>
                        </div>
                        <div class="form-group">
                            <input type="file" name="image">
                            <p class="error"><?php echo $file_err; ?></p>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="myProducts" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once "controller/footer.php"
?>