<?php
include_once "controller/header.php";
include_once "controller/db.php";
include_once "controller/session.php";
//declare the variable and assign to empty string
$name = $username = $email = '';
$name_err = $username_err = $email_err = '';
$noerrors = true;
$user_key = $_SESSION['user_key'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //catch the values of given by the form
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email =  filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    //validate the values
    if (empty($name)) {
        $name_err = '<div class="error">Enter the name</div>';
        $noerrors = false;
    }
    if (empty($username)) {
        $username_err = '<div class="error">Enter the username</div>';
        $noerrors = false;
    }
    if (empty($email)) {
        $email_err = '<div class="error">Enter the email</div>';
        $noerrors = false;
    }
    // //check the errors then upload a image
    // if(empty($target_file)){

    // }else if (isset($_FILES['image'])) {
    //     $target_file = basename($_FILES["image"]["name"]);
    //     $file_size = $_FILES['image']['size'];
    //     $file_tmp = $_FILES['image']['tmp_name']; //temparary directory of the server use
    //     $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    //     $extensions = array("jpeg", "jpg", "png");

    //     //check the extensions
    //     if (in_array($file_type, $extensions) === false) {
    //         $file_err = "extension not allowed, please choose a JPEG or PNG file!";
    //     }

    //     //check the file size
    //     if ($file_size > 2097152) {
    //         $file_err = 'File size must be excately 2 MB!';
    //     }

    //     //check the errors is empty
    //     if (empty($file_err) == true) {
    //         $file_name = $file_tmp;
    //     }
    // }

    //if there is no error then store the values in the database.

    if ($noerrors) {
        if (empty($target_file)) {
            $query = "UPDATE users SET name='$name',email='$email',username='$username' Where user_key='$user_key'";
        }
        // else {
        //     $file_name = addslashes(file_get_contents($file_name));
        //     $query = "UPDATE products SET title='$title',description='$description',price='$price',image='$file_name' Where id='$id'";
        // }
        $sql = $conn->query($query);
        if ($sql == 1) {
            $_SESSION['alert'] = '<div class="alert alert-success">Profile is updated successfully!</div>';
            header('location:profile.php');
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

        input[type="email"] {
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
                <a href="index" class="pull-right m-3">Home</a>
            <?php } ?>
        </h3>   <hr><br><br>
        <div class="col-md-7 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                Update Profile Information
            </div>
            <?php
            $sql = $conn->query("SELECT*FROM users WHERE user_key='$user_key'");
            $row = $sql->fetch_array();
            ?>
            <div class="panel-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" value="<?php echo $row['name'] ?>">
                        <?php echo $name_err ?>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" value="<?php echo $row['username'] ?>">
                        <?php echo $username_err ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?php echo $row['email'] ?>">
                        <?php echo $email_err ?>
                    </div>

                    <!-- <a href="change_password.php" class="text-primary pull-right mt-3">Change a Password?</a> -->
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="profile" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    </div>
</div>
<?php include_once "controller/footer.php"; ?>