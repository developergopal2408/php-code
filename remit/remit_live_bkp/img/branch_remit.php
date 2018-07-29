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
$categoryID = $_SESSION['CATEGORYID'];
$rcompany = mysqli_query($con, "SELECT * FROM remit_company");

include_once 'nav.php';
?>
<body style="padding-top: 6rem;">

    <!--start of container-fluid-->
    <div class="container" >

        <div class="row" ><!--Start of row-->

            <?php
            if (isset($_POST['remit'])) {
                $remitno = $_POST['remitno'];
                $remitId = $_POST['rcompany'];
                $savedate = $_POST['save'];
                $amount = $_POST['amount'];

                if (empty($remitno) or empty($savedate) or empty($amount)) {
                    $error = "<span class='text-danger pull-right'>Please Fill All the details before submit</span><script>setTimeout(\"location.href = 'branch_remit.php';\",2500);</script>";
                } else {
                    $sql = "INSERT INTO remittancedetail(RemitID,branchId,Savedate,Amount,STAFFID,REMITNO,STATUS) VALUES ('$remitId','$branchId','$savedate','$amount','$staffid','$remitno','0')";
                    $res = mysqli_query($con, $sql);
                    $msg = "<span class='text-success pull-right'>Your Remittance has been successfully Posted!</span><script>setTimeout(\"location.href = 'branch_remit.php';\",2500);</script>";
                }
            }
            ?>
            <form  name="branchremit" id="branchremit" method="post" class="form-horizontal">

                <div class="col-md-12"><!--Start of col-md-12-->
                    <h4 class="text-primary pull-left">Pay Remit From Branch </h4>
                    <h5 class=" pull-right">
                        <?php
                        if (isset($error)) {
                            echo $error;
                        } else if (isset($msg)) {
                            echo $msg;
                        }
                        ?>

                    </h5> 
                    <br/>
                    <hr>
                    <div class="col-md-3">
                        <div class="form-group-sm">
                            <label for="exampleInputRemit">Remit Number </label>
                            <input type="text" name="remitno" class="form-control" id="remitno" placeholder="Enter Remit Number" >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group-sm">
                            <label for="SaveDate">Remit Date: </label>
                            <input type="text" maxlength="10" class="form-control" id="save"  name="save" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-sm">
                            <label for="Remit Company">Remittance Company: </label>

                            <select id="rcompany" name="rcompany" class="form-control" required="Please Select Remittance Company">
                                <option value="selectremit">Select Remittance Company</option>
                                <?php
                                foreach ($rcompany as $rco) {
                                    echo '<option value="' . $rco['REMITID'] . '">' . $rco['REMITCOMPANY'] . '</option>';
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-2">

                        <div class="form-group-sm">
                            <label for="exampleInputAmount">Amount </label>
                            <input type="text" name="amount" class="form-control" id="amount" placeholder="Enter Amount" >
                        </div>
                    </div>

                    <div class="col-md-2 ">

                        <input type="submit" name="remit" class="btn btn-sm btn-flat btn-primary pull-right" id="remit" value="Submit" style="margin-top:22px;">

                    </div>
                </div>

            </form><!--end of form-->

        </div><!--end of first row-->

        <hr>
    </div><!--end of container-fluid-->

    <?php
    if ($branchId == '1') {
        $query = "SELECT * FROM remittancedetail as rd LEFT JOIN staffmain as sm ON(rd.STAFFID = sm.STAFFID) LEFT JOIN remit_company as rc ON (rc.RemitID = rd.REMITID) ORDER BY rd.Detailid DESC";
        //$query = "SELECT * FROM remittancedetail ORDER BY Detailid ASC";
        $res = mysqli_query($con, $query);
    } else if ($_SESSION['POSITION'] == 'Branch Incharge' or $_SESSION['POSITION'] == 'Area Incharge' or $_SESSION['IS_APPROVED_BY'] == '1') {
        $query = "SELECT * FROM remittancedetail rd LEFT JOIN staffmain sm ON(rd.STAFFID = sm.STAFFID) LEFT JOIN remit_company rc ON (rc.RemitID = rd.REMITID) WHERE  rd.branchId = '$branchId'  ORDER BY Savedate DESC";
        $res = mysqli_query($con, $query);
    } else {
        $query = "SELECT * FROM remittancedetail rd LEFT JOIN staffmain sm ON(rd.STAFFID = sm.STAFFID) LEFT JOIN remit_company rc ON (rc.RemitID = rd.REMITID) WHERE  rd.STAFFID = '$staffid'  ";
        $res = mysqli_query($con, $query);
    }

    if (!$res or mysqli_num_rows($res) > 0) {
        ?>
        <div class="container">

            <div class="row" style="margin-top:10px;">
                <div class="col-md-12">
                    <div class="pull-right">
                        

                            <?php
                            if ($_SESSION['POSITION'] == 'Branch Incharge' or $_SESSION['POSITION'] == 'Area Incharge' or $_SESSION['IS_APPROVED_BY'] == '1') {
                                ?>
                                <button id="Update" class="btn btn-xs btn-danger" data-href="update_branch_status.php?branchId=<?php echo $branchId; ?>">Approve All Remit</button>
                                <?php
                            }
                            ?>

                        <button id="excel" class="btn btn-xs  btn-success " href="#" onClick ="$('#mytable1').tableExport({type: 'excel', escape: 'false'});">XLS</button>

                    </div>
                </div>
                <hr>
                <div class="col-md-12">

                    <div class="responsive-table">

                        <table id="mytable1" class="table table-striped  table-bordered data-table table-condensed table-hover" style="font-size: 12px; ">

                            <thead>  
                                <tr>
                                    <th>Save Date</th>
                                    <th>REMIT NO</th>
                                    <th>REMIT COMPANY</th>
                                    <th>BRANCH NAME</th>
                                    <th>STAFF NAME</th>
                                    <th>STATUS</th>
                                    <?php if ($branchId != '1') {
                                        ?>
                                        <th>ACTION 
                                            <span class="pull-right">

                                                <?php
                                                if ($_SESSION['POSITION'] == 'Branch Incharge' or $_SESSION['POSITION'] == 'Area Incharge' or $_SESSION['IS_APPROVED_BY'] == '1') {
                                                    ?>
                                                    <button id="Update" class="btn btn-xs btn-danger" data-href="update_branch_status.php?branchId=<?php echo $branchId; ?>">UPDATE</button>
                                                    <?php
                                                }
                                                ?>

                                                <!--<button id="excel" class="btn btn-xs  btn-success" href="#" onClick ="$('#mytable1').tableExport({type: 'excel', escape: 'false'});">XLS</button>-->
                                            </span>
                                        </th>
                                        <?php
                                    }
                                    ?>

                                </tr> 
                            </thead> 
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($res)) {

                                    $firstname = $row['FIRSTNAME'];
                                    $lastname = $row['LASTNAME'];
                                    $staffname = "$firstname $lastname";
                                    //$staffname = $row['STAFFID'];
                                    $status = $row['STATUS'];
                                    ?>
                                    <tr>
                                        <td><?php echo $row['Savedate']; ?></td>
                                        <td><?php echo $row['REMITNO']; ?></td>
                                        <td><?php echo $row['REMITCOMPANY']; ?></td>
                                        <td><?php echo $row['BRANCHNAME']; ?></td>
                                        <td><?php echo $staffname; ?></td>
                                        <td><?php
                                            if ($status == '0') {
                                                echo "<span class='text-danger'>PREPARED</span>";
                                            } else {
                                                echo "<span class='text-success'>APPROVED</span>";
                                            }
                                            ?>
                                        </td>
                                        <?php
                                        if ($categoryID != '1') {
                                            ?>
                                            <td>
                                                <button class="btn btn-xs btn-primary">EDIT</button>


                                                <?php
                                                if ($_SESSION['POSITION'] == 'Branch Incharge' or $_SESSION['POSITION'] == 'Area Incharge' or $_SESSION['IS_APPROVED_BY'] == '1') {
                                                    ?>
                                                    <button id="Update" class="btn btn-xs btn-danger" data-href="update_branch_status.php?branchId=<?php echo $branchId; ?>">UPDATE</button>
                                                    <?php
                                                }
                                                ?>



                                            </td>

                                            <?php
                                        }
                                        ?>

                                    </tr>

                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


            <!--end of sencond row-->
        </div>
        <?php
    }
    ?>


    <?php
    include 'footer2.php';
    ?>
