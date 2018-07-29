<?php
//error_reporting(0);
require_once 'header.php';
if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}
$staffid = $_SESSION['STAFFID'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";
require_once 'nav.php';

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_query = "SELECT * FROM users WHERE STAFFID='$edit_id'";
    $erun = mysqli_query($con, $edit_query);
    if (!$erun or mysqli_num_rows($erun) > 0) {
        $erow = mysqli_fetch_array($erun);
        $eid = $erow['STAFFID'];
        $euname = $uname;
        
    } else {
        header('Location:index.php');
    }
} else {
    header('Location:index.php');
}
?>
<body style="margin-top:80px;">
    <div class="container-fluid">

        <div class="row">

            <div class="col-md-12">
                <h1> Edit Profile <small>Edit Profile Details</small></h1>

                <hr>

                <?php
                if (isset($_POST['submit'])) {

                    $password = mysqli_real_escape_string($con, $_POST['password']);

                    $salt_query = "SELECT * FROM users ORDER BY STAFFID LIMIT 1";
                    $salt_run = mysqli_query($con, $salt_query);
                    $salt_row = mysqli_fetch_array($salt_run);
                    $salt = $salt_row['SALT'];

                    $ipass = crypt($password, $salt);

                    if (empty($password)) {
                        $error_msg = "All (*) fields ar Required";
                    } else {
                        $update_query = "UPDATE `users` SET `PASSWORD` = '$ipass' WHERE STAFFID = '$edit_id'";

                        if (mysqli_query($con, $update_query)) {
                            $msg = "Your Password has been updated";
                            header("refresh:1; url=logout.php");
                        } else {
                            $msg = "Your Password has not been updated";
                        }
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-12 text-capitalize text-center">
                        <?php
                        if (isset($error_msg)) {
                            echo "<span  style='color:red;'>$error_msg</span>";
                        } else if (isset($msg)) {
                            echo "<span  style='color:green;'>$msg</span> <hr>";
                        }
                        ?>

                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <label for="firstname">STAFF NAME: *</label>
                                    </div>

                                    <div class="col-xs-8">
                                        <input type="text" class="form-control"  value="<?php echo $euname; ?>"  disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-xs-6"> <label for="pass">Input New Password: *</label></div>
                                    <div class="col-xs-6"><input type="password" class="form-control" id="password" value="" name="password" placeholder="Enter password"><br/></div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-12">
                            <div class="col-xs-6 pull-right">
                                <input type="submit" name="submit" value="Update User" class="btn btn-primary btn-block">
                            </div>
                            <div class="col-xs-6 pull-left">
                                <a href='index.php' style='text-decoration: none;'><button type="button" class="btn btn-danger btn-block">Cancel</button></a>
                            </div>
                        </div>
                    </form> 


                </div>
            </div>

        </div>
    </div>

    <?php require_once 'footer.php'; ?>