<?php
//error_reporting(0);
ob_start();
session_start();
require_once 'db.php';

if (isset($_POST['submit'])) {
    $staffcode = sprintf("%04d", $_POST['staffcode']);
    $mobile = $_POST['mobile'];

    $sql = "SELECT * FROM StaffMain WHERE StatusID=1 AND Code= '$staffcode' AND Mobile ='$mobile'";
    $res = odbc_exec($connection, $sql);

    $row = odbc_fetch_array($res);
    //print_r($row);
    $dbstaffcode = $row['Code'];
    $dbmobile = $row['Mobile'];
    $StaffID = $row['StaffID'];
    $fname = $row['FirstName'];
    $lname = $row['LastName'];
    $branchid = $row['BranchID'];
	$dbpass = $row['Password'];
    $jid = $row['JobTypeID'];
    $Photo = $row['Photo'];
	$departid = $row['DepartmentID'];
    if ($staffcode == $dbstaffcode AND $mobile == $dbmobile AND $jid != 2) {
        $success = "<span class='text-success'> Login Successful.</span><script>setTimeout(\"location.href = 'page/dashboard.php';\",1500);</script>";
        $_SESSION['Code'] = $dbstaffcode;
        $_SESSION['StaffID'] = $StaffID;
        $_SESSION['uname'] = $fname . $lname;
        $_SESSION['BranchID'] = $branchid;
		$_SESSION['pass'] = $dbpass;
        $_SESSION['JobTypeID'] = $jid;
        $_SESSION['Photo'] = $Photo;
		$_SESSION['DepartmentID'] = $departid;
    } else if ($staffcode == $dbstaffcode AND $mobile == $dbmobile AND $jid == 2) {
        $success = "<span class='text-red'>You are not Authorize!</span>";
    } else {
        $error = "Wrong Staffcode or mobile!";
    }
}
include_once 'header.php';
?>


<div class="login-box">
    <div class="login-logo">
        <a href="index.php" style="color:#FFF;"><b>Branch Reports</b></a>
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
                <input type="password" name="mobile" id="mobile" class="form-control" placeholder="Your Mobile Number" required>
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