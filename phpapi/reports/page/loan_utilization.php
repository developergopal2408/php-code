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
$BranchID = $_SESSION['BranchID'];
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];

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
include_once 'header.php';
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
                <small>Passive Member List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Passive Member List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">

                            <div class="col-sm-12">

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
                                            <?php
                                            if ($_SESSION['BranchID'] == 1) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <select name="oid" id="oid" class="form-control select2" >
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
                                                <?php
                                            }
                                            ?>

                                            <div class="col-sm-2">
                                                <select name="sid" id="sid" class="form-control select2" >
                                                    <option value="">Select Staff/Incharge</option>
                                                    <option value="Fstaff" >Field Staff</option>
                                                    <option value="Incharge" >Incharge</option>

                                                </select>
                                            </div>

                                            <div class="col-sm-1">
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.search form -->
                                    <a href="loan_utilization.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>

                                    <div class="box-tools pull-right" >
                                        <button id="excel" class="btn btn-sm bg-blue" data-toggle = "tooltip" title="Export To Xcell" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                    </div>


                                </div>


                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">

                                <?php
                                if (isset($_POST['oid'])) {
                                    $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['oid'] . "'";
                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $p['Name'] . " - ".$_POST['sid']. " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) ";
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="trial" class="table table-bordered table-striped" > 
                                <thead class="bg-red text-sm"  style="font-size:10px;">
                                    <tr>
                                        <th>MemCode</th>
                                        <th>MembName</th>
                                        <th>LoanDis Date</th>
                                        <th>LoanType</th>
                                        <th>LoanDisAmt</th>
                                        <th>LoanHeading</th>   
                                        <th>UtilizeAmt</th>
                                        <th>OtherAmt</th>
                                        <th>MisUseAmt</th>
                                        <th>Remarks</th>
                                        <th>UtilizeDate</th>
                                        <th>StaffName</th>
                                    </tr>
                                </thead>

                                <?php
                                if (isset($_POST['search'])) {
                                    $ID = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($BranchID > 1) {
                                        if($_POST['sid'] == 'Fstaff'){
                                        $qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,
                                            (l.issuedate)LoanDisDate,t.loantype,(l.loanamount)LoanDisAmt,h.Loanheading,
                                            u.utilizeamt,u.Otheramt,u.misuseamt,u.remarks,(u.savedate)UtilizaDate,s.firstname+' '+s.lastname as StaffName
                                            from member m
                                            join loanmain l on m.memberid=l.memberid and l.officeid='" . $_SESSION['BranchID'] . "'
                                            join loantype t on t.loantypeid=l.loantypeid
                                            join centermain c on c.centerid=m.centerid and c.officeid='" . $_SESSION['BranchID'] . "'
                                            join staffmain s on s.staffid=c.staffid and s.branchid='" . $_SESSION['BranchID'] . "'
                                            join loanheading h on l.loanheadingid=h.loanheadingid 
                                            left join loanutilization u on u.loanmainid=l.loanmainid and u.officeid='" . $_SESSION['BranchID'] . "'
                                            where l.loantypeid<>2 and  l.loanheadingid not in(8,9,10,12,13,14,15,16,70,72,73,74,75,76,77)and
					l.issuedate between '$date1' and '$date2' and m.officeid='" . $_SESSION['BranchID'] . "'";
                                        }else if($_POST['sid'] == 'Incharge'){
                                            $qry = "select m.membercode,m.firstname+' '+ m.lastname as MemberName,(l.issuedate)LoanDisDate,
(select loantype from loantype where l.loantypeid=loantypeid)loantype,
(l.loanamount)LoanDisAmt,
(select loanheading from loanheading where l.loanheadingid=loanheadingid)Loanheading,
u.utilizeamt,u.Otheramt,u.misuseamt,u.remarks,(u.savedate) as UtilizaDate
,s.Firstname+' '+s.lastname as StaffName
from member m, loanmain l, loanutilization u,Staffmain s
                                                    where m.memberid=l.memberid and l.loanmainid=u.loanmainid and s.staffid=u.userid 
                                                     and u.savedate between '$date1' AND '$date2'
                                                     and s.jobtypeid  In(3,6) and m.officeid='" . $_SESSION['BranchID'] . "' and l.officeid='" . $_SESSION['BranchID'] . "' and u.officeid='" . $_SESSION['BranchID'] . "' and s.branchid='" . $_SESSION['BranchID'] . "'
                                                    order by m.membercode";
                                        }
                                    } else {
                                        if($_POST['sid'] == 'Fstaff'){
                                        $qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,
                                            (l.issuedate)LoanDisDate,t.loantype,(l.loanamount)LoanDisAmt,h.Loanheading,
                                            u.utilizeamt,u.Otheramt,u.misuseamt,u.remarks,(u.savedate)UtilizaDate,s.firstname+' '+s.lastname as StaffName
                                            from member m
                                            join loanmain l on m.memberid=l.memberid and l.officeid='$ID'
                                            join loantype t on t.loantypeid=l.loantypeid
                                            join centermain c on c.centerid=m.centerid and c.officeid='$ID'
                                            join staffmain s on s.staffid=c.staffid and s.branchid='$ID'
                                            join loanheading h on l.loanheadingid=h.loanheadingid 
                                            left join loanutilization u on u.loanmainid=l.loanmainid and u.officeid='$ID'
                                            where l.loantypeid<>2 and  l.loanheadingid not in(8,9,10,12,13,14,15,16,70,72,73,74,75,76,77)and
                                             l.issuedate between '$date1' and '$date2' and m.officeid='$ID'";
                                        }else if($_POST['sid'] == 'Incharge'){
                                            $qry = "select m.membercode,m.firstname+' '+ m.lastname as MemberName,(l.issuedate)LoanDisDate,
(select loantype from loantype where l.loantypeid=loantypeid)loantype,
(l.loanamount)LoanDisAmt,
(select loanheading from loanheading where l.loanheadingid=loanheadingid)Loanheading,
u.utilizeamt,u.Otheramt,u.misuseamt,u.remarks,(u.savedate) as UtilizaDate
,s.Firstname+' '+s.lastname as StaffName
from member m, loanmain l, loanutilization u,Staffmain s
                                                    where m.memberid=l.memberid and l.loanmainid=u.loanmainid and s.staffid=u.userid 
                                                     and u.savedate between '$date1' AND '$date2'
                                                     and s.jobtypeid  In(3,6) and m.officeid='$ID' and l.officeid='$ID' and u.officeid='$ID' and s.branchid='$ID'
                                                    order by m.membercode";
                                        }
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    ?>
                                    <tbody  style="font-size:10px;">
                                        <?php
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr >
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['LoanDisDate']; ?></td>
                                                <td><?php echo $res['loantype']; ?></td>
                                                <td><?php echo $res['LoanDisAmt']; ?></td>
                                                <td><?php echo $res['Loanheading']; ?></td>

                                                <td><?php echo $res['utilizeamt']; ?></td>
                                                <td><?php echo $res['Otheramt']; ?></td>
                                                <td><?php echo $res['misuseamt']; ?></td>
                                                <td><?php echo $res['remarks']; ?></td>
                                                <td><?php echo $res['UtilizaDate']; ?></td>
                                                <td><?php echo $res['StaffName']; ?></td>
                                            </tr>																				
                                            <?php
                                        }
                                        ?>
                                    </tbody>

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


