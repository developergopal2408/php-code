<?php
ini_set('session.gc_maxlifetime', 180);
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
                <small>Member's List</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Member's List</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">

                                        <div class="col-sm-3">
                                            <select name="cid" id="cid" class="form-control select2" >
                                                <option value="">Select Center</option>
                                                <?php
                                                $query = "SELECT CenterID,CenterName,CenterCode from centermain where officeid = '" . $_SESSION['BranchID'] . "'";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['CenterID']; ?>" ><?php echo $p['CenterCode'] . " - " . $p['CenterName']; ?></option>;
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>



                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle = "tooltip" title="search"><i class="fa fa-search"></i></button>
                                            <a  href="memberlist.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
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

                            <table id="trial" class="table table-bordered table-striped" bordered="1"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>Member ID</th>
                                        <th>Member Code</th>
                                        <th>Member Name</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['search'])) {
                                        $cid = $_POST['cid'];
                                        $qry = "select OfficeID,CenterID,MemberID,MemberCode,FirstName +' '+ LastName as Mname,Gender,DOB from member where officeid = '" . $_SESSION['BranchID'] . "'  and centerid = '$cid' and Status = 'ACTIVE'";
                                        $res = odbc_exec($connection, $qry);
                                        while ($row = odbc_fetch_array($res)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['MemberID']; ?></td>
                                                <td><?php echo $row['MemberCode']; ?></td>
                                                <td><?php echo $row['Mname']; ?></td>
                                                <td><?php echo $row['Gender']; ?></td>
                                                <td><?php echo $row['DOB']; ?></td>
                                                <td><a href="statement.php?oid=<?php echo $row['OfficeID'];?>&cid=<?php echo $row['CenterID'] ?>&mid=<?php echo $row['MemberID'] ?>" class="btn btn-sm bg-red" target="_blank"><i class="fa fa-print" ></i></a></td>
                                            </tr>

                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>

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




