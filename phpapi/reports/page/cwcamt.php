<?php
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
include_once 'sidebar.php'; //Include Sidebar.php-->
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
//echo $branchName;
include_once 'header.php';
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, 01);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;
//$cdate = "2074/06/24";
?>


<!-- Site wrapper -->
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>

                <small>Center Wise Compulsory Reg Amount</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Center Wise Compulsory Reg Amount</li>
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
                                <?php
                                if ($_SESSION['BranchID'] == 1) {
                                    ?>
                                    <form  action="" method="post" class="form-horizontal" >
                                        <div class=" form-group-sm">
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" >
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = odbc_exec($connection, $sql1);
                                                    while ($rows = odbc_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" ><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                                ?>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#daybook2').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                echo "<span class='text-bold'>" . $bname . " </span>";
                            } else {
                                echo $branchName;
                            }
                            ?>
                            <table id="daybook2" class="table table-condensed table-bordered table-striped">
                                <thead class="bg-red text-sm">
                                    <tr class="text-sm">
                                        <th>CenterCode</th>
                                        <th>CenterName</th>
                                        <th>CenterMeetingType</th>
                                        <th>Com_SetUp_Amt</th>
                                        <th>StaffCode</th>
                                        <th>StaffName</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['id'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and s.officeid='$id' and c.officeid='$id' and a.branchid='$id'";
                                    } else {
                                        $idx = "and s.officeid = '" . $_SESSION['BranchID'] . "' and c.officeid='" . $_SESSION['BranchID'] . "' and a.branchid='" . $_SESSION['BranchID'] . "' ";
                                    }
                                    if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                        $qry = "select c.CenterCode,c.CenterName,(m.intcroption)CenterMeetingType,
                                                (s.Amount)Reg_compulsory_Amt,(a.Code)StaffCode,a.firstname+' '+a.lastname as StaffName
                                                from centersetting s,centermain c,staffmain a,intcroptionloan m
                                                where c.centerid=s.centerid and c.active='Y' and s.typeid=2 and s.Type='SAVING'
                                                and c.staffid=a.staffid and c.meetingtype=m.intcroptionid 
                                                $idx 
                                                order by c.centercode";
                                    } else if($_SESSION['BranchID'] > 1){
                                        $qry = "select c.CenterCode,c.CenterName,(m.intcroption)CenterMeetingType,
                                                (s.Amount)Reg_compulsory_Amt,(a.Code)StaffCode,a.firstname+' '+a.lastname as StaffName
                                                from centersetting s,centermain c,staffmain a,intcroptionloan m
                                                where c.centerid=s.centerid and c.active='Y' and s.typeid=2 and s.Type='SAVING'
                                                and c.staffid=a.staffid and c.meetingtype=m.intcroptionid 
                                                $idx 
                                                order by c.centercode";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['CenterCode']; ?></td>
                                            <td><?php echo $res['CenterName']; ?></td>
                                            <td><?php echo $res['CenterMeetingType']; ?></td>
                                            <td><?php echo $res['Reg_compulsory_Amt']; ?></td>
                                            <td><?php echo $res['StaffCode']; ?></td>
                                            <td><?php echo $res['StaffName']; ?></td>
                                        </tr>
                                        <?php
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
