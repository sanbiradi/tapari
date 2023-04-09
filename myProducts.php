<?php
include_once "controller/header.php";
include_once "controller/db.php";
include "controller/session.php";
$product_key = array();
$user_key = '';
// echo'<div class="alert alert-warning">'.$_SESSION['status'].'</div>';
?>
<div class="container">
    <style>
        .flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .center {
            display: flex;
            justify-content: center;
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
            <?php } else { ?>
                <a href="login" class="btn text-xs btn-md pull-right mt-1">Login</a>
                <a href="signup" class="btn text-xs btn-md pull-right mt-1">Signup</a>
            <?php } ?>
        </h3>
        <hr>
        <?php if (isset($_SESSION['alert'])) {
            echo $_SESSION['alert'];
            unset($_SESSION['alert']);
        } ?>
        <a href="addProduct" class="btn btn-primary btn-md ">Add Product</a>
        <div class="flex mt-4">
            <?php
            $user_key = $_SESSION['user_key'];
            $query =  $conn->query("SELECT*FROM user_products WHERE user_key='$user_key'");
            while ($row = $query->fetch_assoc()) {
                array_push($product_key, $row['product_key']);
            }
            $bind = implode("','", $product_key);
            $query = "SELECT*FROM products WHERE product_key in('" . $bind . "')";
            $sql = $conn->query($query);
            if ($sql->num_rows > 0) {
                while ($row = $sql->fetch_assoc()) :
            ?>
                    <div class="m-4 p-4" style="border: 1px solid #ccc; border-radius:5px;width:200px;">
                        <div class="center">
                            <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['image']); ?>" alt="" height="90px">
                        </div>
                        <div class="mt-3 mb-3">
                            <b><span style="text-transform: capitalize;"><?php echo $row['title'] ?></span></b><br>
                            <span style="text-transform: capitalize;"><?php echo $row['description'] ?></span> <br><br>
                            <b style="text-transform: capitalize;">Price :</b> <?php echo $row['price'] ?>
                        </div>
                        <div>
                            <a href="delete?k=<?php echo base64_encode($row['id']); ?>" class="btn btn-danger btn-xs">Delete</a>
                            <a href="view?k=<?php echo base64_encode($row['id']); ?>" class="text-primary pull-right">View</a>
                        </div>
                    </div>
            <?php
                endwhile;
            } else {
                echo '<div class="error mt-5">No products available</div>';
            }
            ?>
        </div>

    </div>
</div>
<div class="container text-center mb-3">Created by sandip biradi</div>
<?php
include_once "controller/footer.php"
?>