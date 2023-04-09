<?php
include_once "controller/header.php";
include_once "controller/db.php";
//declare the variable and assign to empty string
$name = $username = $email = $password_1 = $password_2 = '';
$name_err = $username_err = $email_err = $password_1_err = $password_2_err = '';
$noerrors = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //catch the values of given by the form
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email =  filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password_1 =  filter_var($_POST['password_1'], FILTER_SANITIZE_STRING);
    $password_2 =  filter_var($_POST['password_2'], FILTER_SANITIZE_STRING);

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
    if (empty($password_1)) {
        $password_1_err = '<div class="error">Enter the password</div>';
        $noerrors = false;
    }
    if (empty($password_2)) {
        $password_2_err = '<div class="error">Enter the confirm password.</div>';
        $noerrors = false;
    }
    if ($password_1 !== $password_2) {
        $password_2_err = '<div class="error">confirm password must be match, Try again later.</div>';
        $noerrors = false;
    }

    //noerrors then store the all values
    if ($noerrors) {
        $uniq = uniqid();
        $password = md5($password_1);
        $stmt = $conn->prepare("INSERT INTO users(user_key,name,username,email,password) VALUES(?,?,?,?,?)");
        $stmt->bind_param('sssss', $uniq, $name, $username, $email, $password);
        if ($stmt->execute()) {
            header('location:login');
            die();
        }
    }
} ?>
<div class="container">
    <link rel="stylesheet" href="css/style-form.css">
    <style>
        html,
        body {
            background: #6665ee;
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <div class="col-md-6 col-md-offset-3">
        <h3>Create Account</h3>
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">
                Sign up
            </div>
            <div class="panel-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control" value="<?php if (isset($name)) {
                                                                                        echo $name;
                                                                                    } else {
                                                                                        echo '';
                                                                                    }; ?>">
                        <?php echo $name_err ?>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control" value="<?php if (isset($username)) {
                                                                                        echo $username;
                                                                                    } else {
                                                                                        echo '';
                                                                                    }; ?>">
                        <?php echo $username_err ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="<?php if (isset($email)) {
                                                                                        echo $email;
                                                                                    } else {
                                                                                        echo '';
                                                                                    }; ?>"?>
                        <?php echo $email_err ?>
                    </div>
                    <div class="form-group">
                        <label for="password_1">Password:</label>
                        <input type="password" name="password_1" class="form-control">
                        <?php echo $password_1_err ?>
                    </div>
                    <div class="form-group">
                        <label for="password_2">Confirm password:</label>
                        <input type="password" name="password_2" class="form-control">
                        <?php echo $password_2_err ?>
                        <a href="login" class="text-primary pull-right mt-4">Already have a account?</a>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "controller/footer.php"; ?>