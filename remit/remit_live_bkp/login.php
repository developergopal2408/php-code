<?php
ob_start();
session_start();
require_once 'connect.php';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, $_POST['STAFFCODE']);
    $password = mysqli_real_escape_string($con, $_POST['PASSWORD']);

    $check_username_query = "SELECT * FROM users WHERE STAFFCODE='$username' ";
    $username_run = mysqli_query($con, $check_username_query);
    if (mysqli_num_rows($username_run) > 0) {
        $row = mysqli_fetch_array($username_run);
        $dbusername = $row['STAFFCODE'];
        $dbpassword = $row['PASSWORD'];
        $status = $row['STATUS'];

        $password = crypt($password, $dbpassword);
        if ($username == $dbusername AND $password == $dbpassword) {
            header('Location:index.php');
            $_SESSION['STAFFID'] = $row['STAFFID'];
            
        } else {
            $error = "Wrong username or password!";
        }
    } else {
        $error = "Wrong username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="img/logo.png">

        <title>Remittance Login | Jeevan Bikas Samaj</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">


        <link href="css/animate.css" rel="stylesheet" type="text/css"/>
        <!-- Custom styles for this template -->
        <link href="css/login.css" rel="stylesheet">


    </head>

    <body>

        <div class="container">

            <form class="form-signin animated shake" action="" method="post">
                <h2 class="form-signin-heading">Welcome To<br> e-Remittance Sewa </h2>
                <label for="Username" class="sr-only">Username</label>
                <input type="text" name="STAFFCODE" id="STAFFCODE" class="form-control" placeholder="Enter Staff Code" required autofocus>
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" name="PASSWORD" id="PASSWORD" class="form-control" placeholder="Password" required>
                <div class="checkbox">
                    <label>
                        <?php
                        if (isset($error)) {
                            echo "<span class='error'>$error</span>";
                        }
                        ?>
                    </label>
                </div>
                <input type="submit" name="submit" value="Sign In" class="btn btn-lg btn-primary btn-block">
            </form>

        </div> <!-- /container -->


        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
