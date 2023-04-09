<?php
// echo "hello";
// exit;
include "controller/header.php";
include "controller/db.php";
session_start();
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
                        ?></a>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="profile">Profile</a></li>
                        <li><a href="myProducts">My products</a></li>
                        <!-- <li><a href="settings.php">Settings</a></li> -->
                        <li><a href="login?lg=<?php echo base64_encode('logout'); ?>">Logout</a></li>
                    </ul>
                </div>
                <a href="home.php" class="pull-right m-3">Home</a>
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
        <div class="flex mt-4">
            <?php
            $query = "SELECT*FROM products";
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
                            <!-- <a href="delete.php?k=<?php echo base64_encode($row['id']); ?>" class="btn btn-danger btn-xs">Delete</a> -->
                            <a href="view?k=<?php echo base64_encode($row['id']); ?>" class="btn btn-primary pull-right">Buy</a>
                        </div>
                    </div>
            <?php
                endwhile;
            } else {
                echo '<div class="error mt-5">No products available</div>';
            }
            ?>
            <div class="container text-center mb-3">Created by sandip biradi</div>
        </div>
    </div>
</div>
<?php
include "controller/footer.php";
?>