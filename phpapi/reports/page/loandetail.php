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
$ndate = $cal->eng_to_nep($year, $month, $day);
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
                <small>Error in Transaction of Loan Detail List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Detail List</li>
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
                                                       echo $sdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date2'])) {
                                                       echo $_POST['date2'];
                                                   } else {
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>

                                            <a href="loandetail.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <table id="trial" class="table table-bordered table-striped" bordered="1" > 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>OfficeID</th>
                                        <th>CenterID</th>
                                        <th>OfficeName</th>
                                        <th>MemberID</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>LoanCr</th>
                                        <th>IntCr</th>
                                        <th>PreDue</th>
                                        <th>IntDue</th>
                                        <th>SaveDate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    $total = 0.0;
                                    if (($date1 AND $date2) == true) {
                                        $qry = "select o.ID,o.Name,m.memberid,m.membercode,m.FirstName +' '+ m.LastName as MemberName,l.LoanCr,l.savedate,l.CenterID,l.intcr,l.pridue,l.intdue
                                            from loandetail l,officedetail o,Member m
                                            where  l.MemberID = m.MemberID and l.OfficeID = o.ID and l.officeid = '".$_SESSION['ID']."' and l.loancr<0 and o.id=m.officeid and l.savedate between '$date1' and '$date2'
                                            order by o.name,m.membercode";

                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['ID']; ?></td>
                                                <td><?php echo $res['CenterID']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['memberid']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['LoanCr']; ?></td>
                                                <td><?php echo $res['intcr']; ?></td>
                                                <td><?php echo $res['pridue']; ?></td>
                                                <td><?php echo $res['intdue']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else if(isset ($_POST['search'])){
                                        $qry = "select o.ID,o.Name,m.memberid,m.membercode,m.FirstName +' '+ m.LastName as MemberName,l.LoanCr,l.savedate,l.CenterID,l.intcr,l.pridue,l.intdue
                                            from loandetail l,officedetail o,Member m
                                            where  l.MemberID = m.MemberID and l.OfficeID = o.ID and l.loancr<0 and o.id=m.officeid and l.officeid = '".$_SESSION['ID']."' and l.savedate between '$date1' and '$date2'
                                            order by o.name,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['ID']; ?></td>
                                                <td><?php echo $res['CenterID']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['memberid']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['LoanCr']; ?></td>
                                                <td><?php echo $res['intcr']; ?></td>
                                                <td><?php echo $res['pridue']; ?></td>
                                                <td><?php echo $res['intdue']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
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


