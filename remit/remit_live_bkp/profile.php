<?php
require_once('header.php');
if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}

$staffid = $_SESSION['STAFFID'];
$query = "SELECT * FROM users us LEFT JOIN staffmain s ON (s.STAFFID = us.STAFFID) WHERE us.STAFFID='$staffid'";
$run = mysqli_query($con, $query);
$row = mysqli_fetch_array($run);
$id = $row['STAFFID'];
$staffcode = $row['STAFFCODE'];
$bcode = $row['BRANCHCODE'];
$fname = $row['FIRSTNAME'];
$lname = $row['LASTNAME'];
$uname = "$fname $lname";
$brnach_name = $row['BRANCHNAME'];
$categoryID = $row['CATEGORYID'];
$password = $row['PASSWORD'];
include_once 'nav.php';
?>
<body >
    <div class="container-fluid" style="margin-top:80px;"> 


        <div class="row">

            <div class="col-md-12">
                <h1>Profile <small>Personal Details</small></h1><hr>


               
                    
                        <center><img id="profile-image" src="img/profile.png" width="100px" class="img-circle img-thumbnail"></center>
                        <br/>
                        <a href="edit_profile.php?edit=<?php echo $id; ?>" class="btn btn-primary pull-right">Change Password</a><br/><hr>

                        <center><h3>Profile Details</h3></center>

                        <br/>
                        <table class="table  table-bordered">
                            <tr>
                                <td width="20%"><b>STAFF ID</b></td>
                                <td width="30%"><?php echo $id; ?></td>
                                <td width="20%"><b>STAFF NAME</b></td>
                                <td width="30%"><?php echo $uname; ?></td>
                            </tr>

                            <tr>
                                <td width="20%"><b>STAFF CODE</b></td>
                                <td width="30%"><?php echo $staffcode; ?></td>
                                <td width="20%"><b>BRANCH NAME</b></td>
                                <td width="30%"><?php echo $brnach_name; ?></td>
                            </tr>
                            <tr>
                                <td width="20%"><b>POSITION </b></td>
                                <td width="30%"><?php echo $_SESSION['POSITION'] ; ?></td>
                                <td width="20%"><b>PASSWORD</b></td>
                                <td width="30%"><?php echo $password; ?></td>
                            </tr>

                        </table>


                        <br/>
                  
                
            </div>

        </div>

<?php require_once 'footer.php'; ?>