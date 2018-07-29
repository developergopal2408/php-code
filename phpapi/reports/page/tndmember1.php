<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 180);
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(180);
ini_set('max_execution_time', 300);
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
include_once 'header.php';

require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
//echo date('Y/m/d');
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, $day);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . "01";
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];

$cdate = $nyr . "/" . $nmonth . "/" . $nday;
?>


<!-- Site wrapper -->
<div class="wrapper">

    <?php
    include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
    include_once 'sidebar.php'; //Include Sidebar.php-->
    ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>

                <small>Total New/Dropout Member's List</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Total New/Dropout Member's List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Total New/Dropout Member's List</h4>
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">

                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date1'])) {
                                                echo $_POST['date1'];
                                            } else {
                                                echo $sdate;
                                            }
                                            ?>">
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date2'])) {
                                                echo $_POST['date2'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Branch</option>
                                                <?php
                                                $query = "SELECT ID,Name,Code from OfficeDetail";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" ><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle = "tooltip" title="search"><i class="fa fa-search"></i></button>
                                            <a  href="tndmember.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" data-toggle = "tooltip" title="Export To Xcell" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                               
                                <?php
                                if (isset($_POST['id'])) {
                                    $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";

                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="trial" class="table table-bordered table-striped" bordered="1"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>Office Code</th>
                                        <th>Office Name</th>
                                        <th>New Added Member</th>
                                        <th>Dropout Member</th>
                                        <th>Net Growth</th>

                                    </tr>
                                </thead>
                               
                                    <?php
                                    if (empty($_POST)) {
                                        $tdm = 0;
                                        $tnm = 0;
                                        $total = 0;
                                        $qry = "select o.code,o.name,
(select count(memberid) from member where regdate>='$sdate' and regdate<='$cdate' and officeid=o.id and status='ACTIVE')NewAddMember,
(select count(memberid) from member where DropOutDate>='$sdate' and DropOutDate<='$cdate' and officeid=o.id and Status='DROPOUT')DropOutMember
from  officedetail o
where ID not in(1,51,52,53,54,55,57,61,80,81,82)
group by o.code,o.name,o.id
order by o.code";
                                        $result = odbc_exec($connection, $qry);
										?>
										<tbody>
										<?php
                                        if (odbc_num_rows($result) > 0) {
                                            while ($res = odbc_fetch_array($result)) {
                                                $tdm += $res['DropOutMember'];
                                                $tnm += $res['NewAddMember'];
                                                 $netmem = $res['NewAddMember'] - $res['DropOutMember'];
                                                 $total += $netmem;
                                                ?>
                                                <tr>
                                                    <td><?php echo $res['code']; ?></td>
                                                    <td><?php echo $res['name']; ?></td>
                                                    <td><?php echo $res['NewAddMember']; ?></td>
                                                    <td><?php echo $res['DropOutMember']; ?></td>
                                                    <td><?php echo $netmem; ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
										<tfoot  class="bg-red text-bold">
                                        <tr>
                                            <td colspan=2>Total</td>
                                            
                                            <td><?php echo $tnm; ?></td>
                                            <td><?php echo $tdm; ?></td>
                                            <td><?php echo $total; ?></td>
                                        </tr>
										</tfoot>
                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $tdm = 0;
                                        $tnm = 0;
                                        $total = 0;
                                        $ID = $_POST['id'];
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        $qry = "select o.code,o.name,
(select count(memberid) from member where regdate>='$date1' and regdate<='$date2' and officeid=o.id and status='ACTIVE')NewAddMember,
(select count(memberid) from member where DropOutDate>='$date1' and DropOutDate<='$date2' and officeid=o.id and Status='DROPOUT')DropOutMember
from  officedetail o
where ID not in(1,51,52,53,54,55,57,61,80,81,82) and ID = '$ID'
group by o.code,o.name,o.id
order by o.code";
                                        $result = odbc_exec($connection, $qry);
										?>
										<tbody>
										<?php
                                        while ($res = odbc_fetch_array($result)) {
                                            $tdm += $res['DropOutMember'];
                                            $tnm += $res['NewAddMember'];
                                            $netmem = $res['NewAddMember'] - $res['DropOutMember'];
                                            $total += $netmem;
                                            ?>
                                            <tr>
                                                <td><?php echo $res['code']; ?></td>
                                                <td><?php echo $res['name']; ?></td>
                                                <td><?php echo $res['NewAddMember']; ?></td>
                                                <td><?php echo $res['DropOutMember']; ?></td>
                                                <td><?php echo $netmem; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
										</tbody>
										<tfoot  class="bg-red text-bold">
                                        <tr>
                                            <td colspan=2>Total</td>
                                            
                                            <td><?php echo $tnm; ?></td>
                                            <td><?php echo $tdm; ?></td>
                                            <td><?php echo $total; ?></td>
                                        </tr>
										</tfoot>
                                        <?php
                                    }
                                    ?>

                                
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php
    include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->

<?php
include_once 'footer.php';
?>


