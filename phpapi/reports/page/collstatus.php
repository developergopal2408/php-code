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
$BranchID = $_SESSION['BranchID'];
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
include_once 'header.php';
?>
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> 
                <small>Center Meeting Status</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Center Meeting Status</li>
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
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date1'])) {
                                                       echo $_POST['date1'];
                                                   } else {
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>

                                        


                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <a href="collstatus.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                echo "<span class='text-bold text-center'>Center Meeting Status - ( " . $_POST['date1'] . "  )</span>";
                            }
                            ?>
                            <table id="trial" class="table table-bordered table-striped" bordered="1" style="width:auto;"> 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>CenterCode</th>
                                        <th>MeetingDate</th>
                                        <th>IsGenerated</th>
                                        <th>IsDownloaded</th>
                                        <th>IsUploaded</th>
                                        <th>IsPosted</th>
                                        <th>StaffCode</th>
                                        <th>StaffName</th>
                                        <th>Mobile</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $date1 = $_POST['date1'];
                                   
                                    if($_SESSION['ID'] == 1){
                                        $id = "";
                                    }else{
                                        $id = "and d.officeid = '".$_SESSION['ID']."'";
                                    }
                                    if (empty($_POST)) {
                                        $qry = "select (o.Code)BranchCode,(o.Name)BranchName,c.CenterCode,M.MeetingDate,m.IsGenerated,(m.isDown)isDownLoad,(m.IsUp)IsUploaded,(m.IsPosted)IsPosted,
                                                (s.Code)StaffCode,s.firstname+' '+s.Lastname as StaffName,s.Mobile
                                                from officedetail o, centermain c,collmaster m,staffMain s
                                                where o.id=c.officeid and o.id=m.officeid and c.centerid=m.centerid and M.meetingDate = '$cdate'
                                                and s.branchid=o.id and m.officeid=s.branchid and s.staffid=m.staffid
                                                order by o.code,c.centercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['BranchCode']; ?></td>
                                                <td><?php echo $res['BranchName']; ?></td>
                                                <td><?php echo $res['CenterCode']; ?></td>
                                                <td><?php echo $res['MeetingDate']; ?></td>
                                                <td><?php echo $res['IsGenerated']; ?></td>
                                                <td><?php echo $res['isDownLoad']; ?></td>
                                                <td><?php echo $res['IsUploaded']; ?></td>
                                                <td><?php echo $res['IsPosted']; ?></td>
                                                <td><?php echo $res['StaffCode']; ?></td>
                                                <td><?php echo $res['StaffName']; ?></td>
                                                <td><?php echo $res['Mobile']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $qry = "select (o.Code)BranchCode,(o.Name)BranchName,c.CenterCode,M.MeetingDate,m.IsGenerated,(m.isDown)isDownLoad,(m.IsUp)IsUploaded,(m.IsPosted)IsPosted,
                                                (s.Code)StaffCode,s.firstname+' '+s.Lastname as StaffName,s.Mobile
                                                from officedetail o, centermain c,collmaster m,staffMain s
                                                where o.id=c.officeid and o.id=m.officeid and c.centerid=m.centerid and M.meetingDate = '$date1' 
                                                and s.branchid=o.id and m.officeid=s.branchid and s.staffid=m.staffid
                                                order by o.code,c.centercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                           
                                            ?>
                                            <tr>
                                                <td><?php echo $res['BranchCode']; ?></td>
                                                <td><?php echo $res['BranchName']; ?></td>
                                                <td><?php echo $res['CenterCode']; ?></td>
                                                <td><?php echo $res['MeetingDate']; ?></td>
                                                <td><?php echo $res['IsGenerated']; ?></td>
                                                <td><?php echo $res['isDownLoad']; ?></td>
                                                <td><?php echo $res['IsUploaded']; ?></td>
                                                <td><?php echo $res['IsPosted']; ?></td>
                                                <td><?php echo $res['StaffCode']; ?></td>
                                                <td><?php echo $res['StaffName']; ?></td>
                                                <td><?php echo $res['Mobile']; ?></td>
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
        <!--/.content -->
    </div>
    <!--/.content-wrapper -->
    <?php
    include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->
<?php
include_once 'footer.php';
?>


