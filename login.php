<?php
include "controller/header.php";
include "controller/db.php";
if (isset($_GET['lg'])) {
    session_start();
    unset($_SESSION['status']);
    session_unset();
    session_destroy();
}
session_start();
$username_err = $password_err = '';
$username = '';
$password = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //catch the values of given by the form 
    $username = $password = '';
    // $email_err = $email =
    $noerrors = true;
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    // $email =  filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password =  filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    //validate the values
    if (empty($username)) {
        $username_err = '<div class="error">Enter the username</div>';
        $noerrors = false;
    }
    // if (empty($email)) {
    //     $email_err = '<div class="error">Enter the email</div>';
    //     $noerrors = 0;
    // }
    if (empty($password)) {
        $password_err = '<div class="error mb-4">Enter the password</div>';
        $noerrors = false;
    }
    if ($noerrors) {
        if (!empty($_POST['rememberme'])) {
            setcookie("username", $username, time() + 2628000);
            setcookie("password", $password, time() + 2628000);
        }
        else{
            setcookie("username",'');
            setcookie("password",'');
        }
        $noerrors = true;
        $password = md5($password);

        $user_query = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($user_query->num_rows == 0) {
            $username_err = '<div class="error">Username is not exist.</div>';
            $noerrors = false;
            header("Location:login?msg=User not found!");
        }

        $password_query = $conn->query("SELECT * FROM users WHERE password='$password'");
        if ($user_query->num_rows == 0) {
            $password_err = '<div class="error">Password is Wrong!</div>';
            $noerrors = false;
            header("Location:login?msg=User not found!");
        }

        if ($noerrors) {
            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $_SESSION['user_key'] = $row['user_key'];
                }
                $_SESSION['status'] = 'login';
                header("Location:index");
            }
        }
  
        // $stmt = $conn->prepare("SELECT user_key,username,name,password FROM users WHERE username=? AND password=?");
        // $stmt->bind_param('ss', $username, $password);
        // $username = $username;
        // $password= $password;
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $row = $result->fetch_array();
        // print_r($row);
        // exit;
    }
    $conn->close();
} ?>
<div class="container">
    <style>
        html,
        body {
            background: #6665ee;
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="css/style-form.css">
    <div class="col-sm-4 col-sm-offset-2">
        <h3>Login your account</h3>
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">
                Login
            </div>
            <div class="panel-body">
                <?php if (isset($_GET['msg'])) : ?>
                    <div class="alert alert-danger "><?php echo $_GET['msg']; ?></div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php if (isset($_COOKIE['username'])) {
                                                                                            echo $_COOKIE['username'];
                                                                                        } else {
                                                                                            echo '';
                                                                                        }; ?>">
                        <?php echo $username_err; ?>
                    </div>
                    <div class="form-group">

                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" value="<?php if (isset($_COOKIE['password'])) {
                                                                                            echo $_COOKIE['password'];
                                                                                        } else {
                                                                                            echo '';
                                                                                        }; ?>">
                        <?php echo $password_err; ?>
                        <!-- <a href="forgetPassword.php" class="btn text-primary">Forget password?</a> -->
                        <div class="mt-2">
                            <label for="rememberme">Remember me</label>
                            <input type="checkbox" name="rememberme" id="rememberme" class="pull-left mr-1" <?php if (isset($_COOKIE['username'])) {
                                                                                            echo "checked";
                                                                                        } else {
                                                                                            echo '';
                                                                                        }; ?>>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="signup" class="pull-right">Have'nt Account?</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        document.querySelector('.alert').remove();
    }, 3000);
</script>
<?php include_once "controller/footer.php" ?>