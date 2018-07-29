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

    <h4 class="text-center">List Of Remit Made By Branch </h4>
    <hr>
    <?php
    if ($branchId == '1') {
        $query = "SELECT * FROM remittancedetail as rd JOIN remit_company as rc ON (rc.RemitID = rd.REMITID) JOIN staffmain as sm ON(rd.STAFFID = sm.STAFFID)  ORDER BY rd.Detailid ASC";
        //$query = "SELECT * FROM remittancedetail ORDER BY Detailid ASC";
        $res = mysqli_query($con, $query);
    } else {
        $query = "SELECT * FROM remittancedetail rd LEFT JOIN staffmain sm ON(rd.STAFFID = sm.STAFFID) LEFT JOIN remit_company rc ON (rc.RemitID = rd.REMITID) WHERE  rd.branchId = '$branchId'  ";
        $res = mysqli_query($con, $query);
    }
    if (!$res or mysqli_num_rows($res) > 0) {
        ?>
        <div class="container">

            <div class="row" style="margin-top:10px;">
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
                                                <button id="excel" class="btn btn-xs  btn-success" href="#" onClick ="$('#mytable1').tableExport({type: 'excel', escape: 'false'});">XLS</button>
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
                                        <button class="btn btn-sm btn-primary">EDIT</button>
                                        <?php
                                        if ($_SESSION['POSITION'] == 'Branch Incharge' or $_SESSION['POSITION'] == 'Area Incharge') {
                                            ?>
                                            <button class="btn btn-sm btn-danger">UPDATE</button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    </tr>
                                    <?php
                                }
                                ?>


                                
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
    } else {
        echo "No Record Available Right Now";
    }
    ?>


<?php
include 'footer2.php';
?>
