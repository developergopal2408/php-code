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

                <small>Loan Disburse According To Age</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Disburse According To Age</li>
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
                                            <?php
                                        }
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
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
                                if ($_POST['id']) {
                                    echo "<span class='text-bold'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</span>";
                                } else {
                                    echo "<span class='text-bold'>" . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</span>";
                                }
                            } 
                            ?>
                            <table id="daybook2" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red ">
                                    <tr class="text-sm">
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>DOB</th>
                                        <th>Age</th>
                                        <th>RegDate</th>
                                        <th>LoanType</th>
                                        <th>DisDate</th>
                                        <th>LoanHeading</th>
                                        <th>LoanNo</th>
                                        <th>LoanDis</th>
                                        <th>Outstanding</th>
                                        <th>DisStaff</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                     if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and o.id = '$id'";
                                    } else {
                                        $idx = "and o.id = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "Select m.MemberCode,m.firstname+' '+m.lastname MemberName,m.DOB,m.regdate,
                                            t.LoanType,(l.issuedate)DisDate,h.LoanHeading,l.LoanNo,(l.loanamount)
LoanDis,sum(v.loandr-v.loancr) as Outstanding,s.firstname+' '+s.lastname as DisStaff
from officedetail o, loanmain l,loandetail v, member m,loantype t,loanheading h,staffmain s
where m.memberid=l.memberid and v.memberid=m.memberid and l.userid=s.staffid
and v.loanmainid=l.loanmainid and t.loantypeid=l.loantypeid
and o.id=l.officeid and o.id=v.officeid and m.officeid=o.id and h.loanheadingid=l.loanheadingid
and l.issuedate between '$date1' AND '$date2' $idx
group by m.membercode,m.firstname,m.lastname,m.DOB,t.loantype,l.issuedate,l.loanamount,h.loanheading,
s.firstname,s.lastname,l.LoanNo,m.regdate
having sum(v.loandr-v.loancr)<>0.0
order by h.loanheading";
                                    } else {
                                        $qry = "Select m.MemberCode,m.firstname+' '+m.lastname MemberName,m.DOB,m.regdate,
                                            t.LoanType,(l.issuedate)DisDate,h.LoanHeading,l.LoanNo,(l.loanamount)
                                            LoanDis,sum(v.loandr-v.loancr) as Outstanding,s.firstname+' '+s.lastname as DisStaff
                                            from officedetail o, loanmain l,loandetail v, member m,loantype t,loanheading h,staffmain s
                                            where m.memberid=l.memberid and v.memberid=m.memberid and l.userid=s.staffid
                                            and v.loanmainid=l.loanmainid and t.loantypeid=l.loantypeid
                                            and o.id=l.officeid and o.id=v.officeid and m.officeid=o.id and h.loanheadingid=l.loanheadingid
                                            and l.issuedate between '$date1' AND '$date2' $idx
                                            group by m.membercode,m.firstname,m.lastname,m.DOB,
                                            t.loantype,l.issuedate,l.loanamount,h.loanheading,s.firstname,
                                            s.lastname,l.LoanNo,m.regdate
                                            having sum(v.loandr-v.loancr)<>0.0
                                            order by h.loanheading";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
										$dob = $res['DOB'];
											  list($yr1, $mn1, $dy1) = explode("/", $cdate);
											  $npdate = $cal->nep_to_eng($yr1, $mn1, $dy1);
											  $yr = $npdate['year'];
											  $mn = $npdate['month'];
											  $dy = $npdate['date'];
											  $fdate = $yr . "/" . $mn . "/" . $dy;
											  list($yr2, $mn2, $dy2) = explode("/", $dob);
											  $npdates = $cal->nep_to_eng($yr2, $mn2, $dy2);
											  $yrs = $npdates['year'];
											  $mns = $npdates['month'];
											  $dys = $npdates['date'];
											  $tdate = $yrs . "/" . $mns . "/" . $dys;
											  $start = strtotime($fdate);
											  $end = strtotime($tdate);
											  $diff = ceil(abs($start - $end) / 86400);
											  //print_r($diff);
											  $age = ceil(abs($diff/365));
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['MemberCode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['DOB']; ?></td>
                                            <td><?php echo $age; ?></td>
                                            <td><?php echo $res['regdate']; ?></td>
                                            <td><?php echo $res['LoanType']; ?></td>
                                            <td><?php echo $res['DisDate']; ?></td>
                                            <td><?php echo $res['LoanHeading']; ?></td>
                                            <td><?php echo $res['LoanNo']; ?></td>
                                            <td><?php echo $res['LoanDis']; ?></td>
                                            <td><?php echo $res['Outstanding']; ?></td>
                                            <td><?php echo $res['DisStaff']; ?></td>
                                            
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
