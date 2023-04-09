<?php
include_once "controller/db.php";
session_start();
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $product_key = '';
    $id = filter_var($_GET['k'], FILTER_SANITIZE_STRING);
    $id = base64_decode($id);
    $sql = $conn->query("SELECT * FROM products WHERE id='$id'");
    while ($row = $sql->fetch_assoc()) {
        $product_key = $row['product_key'];
    }
    $conn->query("DELETE FROM products Where id='$id';");
    if ($conn->query("DELETE FROM user_products WHERE product_key='$product_key'")) {
        $_SESSION['alert'] = '<div class="alert alert-danger">Product is deleted successfully!</div>';
        header('location:myProducts');
        die();
    }
}
include_once "controller/header.php";
?>

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
        <div class="mt-5 text-center">
            <h4>Are you sure you want to delete your product?</h4>
            <br>
            <form action="" method="post">
                <button type="submit" class="btn btn-danger pl-5 pr-5 mr-4">Yes</button>
                <a href="profile" class="btn btn-default pl-5 pr-5">No</a>
            </form>
        </div>
    </div>
</div>
<?php
include_once "controller/footer.php";
?>
