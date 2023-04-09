<?php
include_once "controller/header.php";
include_once 'controller/db.php';
include_once "controller/session.php";
?>
<div class="container">
    <style>
        input[type="text"] {
            border-color: transparent;
            outline: none;
            width: 100%;
        }
        .flex {
            display: block;
            margin: auto;
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
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="myProducts.php">My products</a></li>
                        <!-- <li><a href="settings.php">Settings</a></li> -->
                        <li><a href="login.php?lg=<?php echo base64_encode('logout'); ?>">Logout</a></li>
                    </ul>
                </div>
                <a href="home" class="pull-right m-3">Home</a>
            <?php } ?>
        </h3>
        <hr><br><br>
        <div class="col-md-7 col-md-offset-3">
            <h3>Product Information</h3>
            <?php
            $id = filter_var($_GET['k'], FILTER_SANITIZE_STRING);
            $id = base64_decode($id);
            if (isset($id)) {
                $sql = $conn->query("SELECT*FROM products WHERE id='$id'");
                $row = $sql->fetch_array();
            ?>
                <div class="flex" style="width:200px;">
                    <div class="mt-4">
                        <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['image']); ?>" alt="" height="90px" title="<?php echo $row['title']; ?>" data-toggle="tooltip">
                    </div>
                    <div class="mt-5 ml-5">
                        <b>Title: </b><span style="text-transform: capitalize;"><?php echo $row['title'] ?></span><br>
                        <b>Description: </b><span style="text-transform: capitalize;"><?php echo $row['description'] ?></span> <br><br>
                        <b style="text-transform: capitalize;">Price :</b> <span><?php echo $row['price'] ?>Rs</span>
                    </div> <br>
                </div>
                <div class="mt-5">
                    <a href="edit?k=<?php echo base64_encode($row['id']); ?>" class="btn btn-primary">Edit Product</a>
                    <a href="delete?k=<?php echo base64_encode($row['id']); ?>" class="ml-3" style="color:red;">Delete</a>
                </div>

            <?php
            }
            ?>
        </div>
    </div>
</div>
<?php include_once "controller/footer.php";
?>