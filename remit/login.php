<?php
ob_start();
session_start();
require_once 'db.php';
if (isset($_POST['submit'])) {
    $staffcode = sprintf("%04d", $_POST['staffcode']);
    $mobile = md5($_POST['password']);
    $sql = "SELECT * FROM StaffMain WHERE StatusID=1 AND Code= '$staffcode' AND Password ='$mobile'";
    $res = odbc_exec($connection, $sql);
    $row = odbc_fetch_array($res);
    $dbstaffcode = $row['Code'];
    $dbmobile = $row['Password'];
    $StaffID = $row['StaffID'];
    $fname = $row['FirstName'];
    $lname = $row['LastName'];
    $branchid = $row['BranchID'];
    $jid = $row['JobTypeID'];
    if ($staffcode == $dbstaffcode AND $mobile == $dbmobile) {
        $success = "<span class='text-success'> Login Successful.</span><script>setTimeout(\"location.href = 'page/dashboard.php';\",1500);</script>";
        $_SESSION['Code'] = $dbstaffcode;
        $_SESSION['StaffID'] = $StaffID;
        $_SESSION['uname'] = $fname . $lname;
        $_SESSION['BranchID'] = $branchid;
        $_SESSION['JobTypeID'] = $jid;
    } else {
        $error = "<span class='text-red'>Wrong Staffcode or mobile!</span>";
    }
}
include_once 'header.php';
?>
<div class="login-box">
    <div class="login-logo">
        <a href="index.php" style="color:#FFF;"><b>E-REMITTANCE SEWA</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg text-red">Sign in to Lock Your Session</p>

        <form action="" method="post">
            <div class="form-group has-feedback">
                <input type="text" name="staffcode" id="staffcode" class="form-control" placeholder="Enter Staffcode" required autofocus>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" id="password" class="form-control" placeholder="Your Finlitex Password Here" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <?php
                            if (isset($error)) {
                                echo $error;
                            } else if (isset($success)) {
                                echo $success;
                            }
                            ?>
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<?php
include_once 'footer.php';
?>