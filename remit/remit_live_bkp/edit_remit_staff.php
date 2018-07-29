<?php
include 'header.php';
if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}
$edit_id = $_GET['detailid'];
$staffid = $_SESSION['STAFFID'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";
$branchId = $_SESSION['BRANCHID'];
$categoryID = $_SESSION['CATEGORYID'];
$rcompany = mysqli_query($con, "SELECT * FROM remit_company");

$sql = "SELECT * FROM remittancedetail re LEFT JOIN remit_company rc ON(rc.REMITID = re.RemitID) WHERE re.Detailid = '$edit_id'";
$run = mysqli_query($con, $sql);
$edit_row = mysqli_fetch_array($run);
$save = $edit_row['Savedate'];
$remitcompany = $edit_row['REMITCOMPANY'];
$remitamount = $edit_row['Amount'];
$remitno = $edit_row['REMITNO'];

include_once 'nav.php';
?>
<body style="padding-top: 6rem;">

    <!--start of container-fluid-->
    <div class="container" >

        <div class="row" ><!--Start of row-->

            <?php
            if (isset($_POST['edits'])) {
                $remitno = $_POST['remitno'];
                $remitId = $_POST['rcompany'];
                $savedate = $_POST['save'];
                $amount = $_POST['amount'];

                if (empty($remitno) or empty($savedate) or empty($amount)) {
                    $error = "<span class='text-danger pull-right'>Please Fill All the details before submit</span><script>setTimeout(\"location.href = 'branch_remit.php';\",2500);</script>";
                } else {
                    $sql = "UPDATE remittancedetail SET RemitID = '$remitId',branchId = '$branchId', Savedate = '$savedate', Amount = '$amount', REMITNO = '$remitno',STATUS = '0' WHERE Detailid = '$edit_id'";
                    $res = mysqli_query($con, $sql);
                    
                    
                    $msg = "<span class='text-success pull-right'>Your Remittance has been successfully Updated!</span><script>setTimeout(\"location.href = 'branch_remit.php';\",2500);</script>";
                    $remitno = $_POST['remitno'];
                    $remitId = $_POST['rcompany'];
                    $savedate = $_POST['save'];
                    $amount = $_POST['amount'];
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
                            <input type="text" name="remitno" class="form-control" id="remitno" placeholder="Enter Remit Number" value="<?php echo $remitno;?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group-sm">
                            <label for="SaveDate">Remit Date: </label>
                            <input type="text" maxlength="10" class="form-control" id="save"  name="save" value="<?php echo $save;?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-sm">
                            <label for="Remit Company">Remittance Company: </label>

                            <select id="rcompany" name="rcompany" class="form-control" >
                                <!--<option value="selected" >Please Select Remit Type</option>-->
                                <?php
                                foreach ($rcompany as $rco) {
                                   // echo '<option value="' . $rco['REMITID'] . '" "if($remitcompany != false){echo "selected";} ">' . $rco['REMITCOMPANY'] . '</option>';
                                    echo "<option value='".$rco['REMITID']."' ".(($rco['REMITCOMPANY'] == $remitcompany)?"selected":"").">".  $rco['REMITCOMPANY']."</option>";
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-2">

                        <div class="form-group-sm">
                            <label for="exampleInputAmount">Amount </label>
                            <input type="text" name="amount" class="form-control" id="amount" placeholder="Enter Amount" value="<?php echo $remitamount;?>">
                        </div>
                    </div>

                    <div class="col-md-2 ">

                        <input type="submit" name="edits" id="edits" class="btn btn-sm btn-flat btn-primary pull-right" id="remit" value="Submit" style="margin-top:22px;">

                    </div>
                </div>

            </form><!--end of form-->

        </div><!--end of first row-->

        <hr>
    </div><!--end of container-fluid-->

   

    <?php
    include 'footer2.php';
    ?>
