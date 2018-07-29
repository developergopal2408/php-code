<?php
include 'header.php';
if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}
$staffid = $_SESSION['STAFFID'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";
$branchId = $_SESSION['BRANCHID'];
$branchnames = $_SESSION['BRANCHNAME'];
$categoryID = $_SESSION['CATEGORYID'];
$position = $_SESSION['POSITION'];


include_once 'nav.php';
?>
<body style="padding-top: 6rem;">

    <!--start of container-fluid-->
    <div class="container" >
        <h4 class="text-primary">Manage Staffs</h4>
        <hr>
        <div class="row" ><!--Start of row-->
            <div class="col-md-12">
                <?php
                if ($position == 'Branch Incharge' or $position == 'Area Incharge') {
                    $query = "SELECT * FROM users us LEFT JOIN staffmain s ON(s.STAFFID = us.STAFFID) WHERE s.BRANCHID = '$branchId'";
                    $run = mysqli_query($con, $query);
                    if (!$run or mysqli_num_rows($run) > 0) {
                        ?>
                        <div class="responsive-table">

                            <table id="mytable" class="table table-striped  table-bordered data-table table-condensed table-hover" style="font-size: 12px; ">
                                <thead>
                                    <tr>
                                        <th>STAFF NAME</th>
                                        <th>BRANCH NAME</th>
                                        <th>ROLE</th>
                                        <th>Remit Approver</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_array($run)) {
                                        $firstname = $row['FIRSTNAME'];
                                        $lastname = $row['LASTNAME'];
                                        $staffname = "$firstname $lastname";
                                        $branchname = $row['BRANCHNAME'];
                                        $staffid = $row['STAFFID'];
                                        $role = $row['POSITION'];
                                        $check = $row['STATUS'];
                                        $approver = $row['IS_APPROVED_BY'];
                                        ?>
                                        <tr>
                                            <td><?php echo $staffname; ?></td>
                                            <td><?php echo $branchname; ?></td>
                                            <td><?php echo $role; ?></td>
                                            <td><?php echo $approver; ?></td>
                                            <td><?php echo $check; ?></td>
                                            <td>
                                                <?php
                                                 if($approver == '1'){
                                                ?>
                                               <button id="upstaff" class="btn btn-xs btn-primary" data-href="revert_staff.php?staffid=<?php echo $staffid; ?>">Revert To Field Staff</button> 
                                               <?php
                                                }else{
                                                    ?>
                                               <button id="staff" class="btn btn-xs btn-danger" data-href="update_staff.php?staffid=<?php echo $staffid; ?>">UPDATE To Branch Incharge</button> 
                                              
                                               <?php
                                                }
                                               ?>
                                            </td>
                                        </tr>

                                        <?php
                                    }
                                    ?>
                                </tbody>


                            </table>
                        </div>


                        <?php
                    }
                }
                ?>


            </div>

        </div><!--end of first row-->

    </div><!--end of container-fluid-->



    <?php
    include 'footer2.php';
    ?>
