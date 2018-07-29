<?php
date_default_timezone_set('Asia/Kathmandu');
require_once('nepali_calendar.php');
require_once('functions.php');
$cal = new Nepali_Calendar();



include_once 'header.php';

if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}
$staffid = $_SESSION['STAFFID'];
$query = mysqli_query($con, "SELECT * FROM users us LEFT JOIN staffmain s ON(s.STAFFID = us.STAFFID) WHERE us.STAFFID = '$staffid'");
$row = mysqli_fetch_array($query);
$_SESSION['firstname'] = $row['FIRSTNAME'];
$_SESSION['lastname'] = $row['LASTNAME'];
$_SESSION['BRANCHNAME'] = $row['BRANCHNAME'];
$_SESSION['BRANCHID'] = $row['BRANCHID'];
$branchId = $_SESSION['BRANCHID'];
$_SESSION['BRANCHCODE'] = $row['BRANCHCODE'];
$_SESSION['STAFFCODE'] = $row['STAFFCODE'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";

$rcompany = mysqli_query($con, "SELECT * FROM remit_company");

include_once 'nav.php';
?>

<body style="padding-top: 6rem;">

    <div class="container" >

        <div class="row">

            <?php
            if (isset($_POST['send'])) {
                $remitno = $_POST['remitno'];
                $remitId = $_POST['rcompany'];
                //$remitdate = $_POST['remitdate'];
				$remitdate = date('Y-m-d');
                $amount = $_POST['amount'];
                $raddress = $_POST['raddress'];
                if (empty($remitno) or empty($amount) or empty($raddress)) {
                    $error = "<span class='text-danger pull-right'>Please Fill All the details before submit</span><script>setTimeout(\"location.href = 'send_remit.php';\",2500);</script>";
                } else {
                    $sql = "INSERT INTO send_remit(REMITID,BRANCHID,REMITDATE,AMOUNT,ADDRESS,STAFFID,REMITNO) VALUES ('$remitId','$branchId','$remitdate','$amount','$raddress','$staffid','$remitno')";
                    $res = mysqli_query($con, $sql);
                    $msg = "<span class='text-success pull-right'>Your Remittance has been successfully Posted!</span><script>setTimeout(\"location.href = 'send_remit.php';\",2500);</script>";
                }
            }
            ?>


            <div class="col-md-12">
                <?php 
                if($branchId != '1'){
                ?>
                <form class="form-horizontal" id="sendremit" method="post" name="sendremit" role="form" >
                    <div class="col-md-12"><!--Start of col-md-12-->
                        <h4 class="text-primary pull-left">Send Remit </h4>
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
                                <label for="Remit Company">Remittance Company: </label>

                                <select id="rcompany" name="rcompany" class="form-control" >
                                    <option value="selectremit">Select Remittance Company</option>
                                    <?php
                                    foreach ($rcompany as $rco) {
                                        echo '<option value="' . $rco['REMITID'] . '">' . $rco['REMITCOMPANY'] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group-sm">
                                <label for="InputRemitName">Remit No</label>
                                <input type="text" maxlength="18" class="form-control" name="remitno" id="remitno" placeholder="Enter Remit Number" >
                            </div>
                        </div>

                        <!--<div class="col-md-2">
                            <div class="form-group-sm">
                                <label for="InputRemitDate">Remit Date</label>
                                <input type="text" class="form-control" name="remitdate" id="remitdate" >
                            </div>
                        </div>-->
                        <div class="col-md-3">
                            <div class="form-group-sm">
                                <label for="InputRemitAddress">Receiver Address</label>
                                <textarea class="form-control" name="raddress" id="raddress" style="height:30px;" placeholder="City/District"></textarea>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group-sm">
                                <label for="InputRemitAmount">Amount</label>
                                <input type="text" class="form-control" name="amount" id="amount" >
                            </div>
                        </div>

                        <div class="col-md-1 ">

                            <input type="submit" name="send" id="send" class="btn btn-sm btn-flat btn-primary pull-right"  value="Submit" style="margin-top:22px;">

                        </div>

                    </div>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>


    <?php
    if ($branchId == '1') {
        $query = "SELECT * FROM send_remit as rd LEFT JOIN staffmain as sm ON(rd.STAFFID = sm.STAFFID) LEFT JOIN remit_company as rc ON (rc.RemitID = rd.REMITID) ORDER BY rd.ID DESC";
        $res = mysqli_query($con, $query);
    } else if ($branchId != '1' ) {
        $query = "SELECT * FROM send_remit rd LEFT JOIN staffmain sm ON(rd.STAFFID = sm.STAFFID) LEFT JOIN remit_company rc ON (rc.RemitID = rd.REMITID) WHERE  rd.BRANCHID = '$branchId'  ";
        $res = mysqli_query($con, $query);
    } else {
        $query = "SELECT * FROM send_remit rd LEFT JOIN staffmain sm ON(rd.STAFFID = sm.STAFFID) LEFT JOIN remit_company rc ON (rc.RemitID = rd.REMITID) WHERE  rd.STAFFID = '$staffid'  ";
        $res = mysqli_query($con, $query);
    }

    if (!$res or mysqli_num_rows($res) > 0) {
        ?>
        <div class="container">

            <div class="row" style="margin-top:10px;">
                <div class="col-md-12">
                    <hr>
                    <div class="pull-left">
                        <h4 class="text-primary">
                            <?php 
                            if($branchId == '1'){
                                echo "Remittance Record";
                            }else{
                            ?>
                            Remittance Made From <?php echo $_SESSION['BRANCHNAME']; }?>
                        
                        </h4>
                        <hr>
                    </div>
                    <div class="pull-right">

                        <button id="excel" class="btn btn-xs  btn-success " href="#" onClick ="$('#sendtable').tableExport({type: 'excel', escape: 'false'});">Export to Excel</button>
                        <br/>
                        <hr>
                    </div>

                </div>

                <div class="col-md-12">

                    <div class="responsive-table">

                        <table id="sendtable" class="table table-striped  table-bordered data-table table-condensed table-hover" style="font-size: 12px; ">

                            <thead>  
                                <tr>
                                    <th>Remit Date</th>
                                    <th>REMIT NO</th>
                                    <th>REMIT COMPANY</th>
                                    <th>BRANCH NAME</th>
                                    <th>RECEIVER ADDRESS</th>
                                    <th>AMOUNT</th>
                                    <th>STAFF NAME</th>



                                </tr> 
                            </thead> 
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($res)) {
									
									
									list($year, $month, $day) = explode('-', $row['REMITDATE']);
									$nepdate = $cal->eng_to_nep($year, $month, $day);
									$nyr = $nepdate['year'];
									$nmonth = $nepdate['month'];
									$nday = $nepdate['date'];
									$cdate = $nyr . "-" . $nmonth . "-" . $nday;

                                    $firstname = $row['FIRSTNAME'];
                                    $lastname = $row['LASTNAME'];
                                    $staffname = "$firstname $lastname";
                                    $id = $row['ID'];
                                    $amounts = $row['AMOUNT'];
                                    ?>
                                    <tr>
                                        <td><?php echo $cdate; ?></td>
                                        <td><?php echo $row['REMITNO']; ?></td>
                                        <td><?php echo $row['REMITCOMPANY']; ?></td>
                                        <td><?php echo $row['BRANCHNAME']; ?></td>
                                        <td><?php echo $row['ADDRESS']; ?></td>
                                        <td><?php echo $amounts; ?></td>
                                        <td><?php echo $staffname; ?></td>



                                    </tr>

                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


            <!--end of second row-->
        </div>
        <?php
    }
    ?>


    <?php
    include_once 'footer2.php';
    ?>
