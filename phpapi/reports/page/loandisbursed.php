<?php
ob_start();
session_start();
require_once '../db.php';
//$connection = odbc_connect("Driver={SQL Server};Server=JBS-SERVER\JBS;Database=FinliteXV2;", "", "");
//$connection = odbc_connect("Driver={SQL Server};Server=JBS-DB;Database=FinliteX;", "", "");
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

$d = date_create();
$startdate = date_create($d->format('Y-m-1'))->format('Y-m-d');


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

                <small>Loan Disbursed</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Loan Disbursed</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Loan Disbursed List</h4>
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">




                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" >
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" >
                                        </div>

                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" required>
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
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#daybook2').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                Loan Disbursed ( <?php
                                if (isset($_POST['date1']) AND ( $_POST['date2'])) {
                                    echo $_POST['date1'] . " - " . $_POST['date2'];
                                } else {
                                    echo $cdate;
                                }
                                ?> ): 
                                <?php
                                if (!empty($_POST['id'])) {
                                    $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";

                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="daybook2" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red ">
                                    <tr class="text-sm" style="font-size:10px;">
                                        <th>S.No</th>
                                        <th>MCode</th>
                                        <th>MName</th>
                                        <th>SpouseFather</th>
                                        <th>FatherInLaw</th>
                                        <th>Date</th>
                                        <th>Ltype</th>
                                        <th>LHeading</th>
                                        <th>InttRate</th>
                                        <th>TnsType</th>
                                        <th>InsNo</th>
                                        <th>LAmt</th>
                                        <th>InstAmt</th>
                                    </tr>

                                </thead>
                                <?php
                                if (empty($_POST)) {
                                    $counter = 0;
                                    $ln = 0;
                                    $query = "SELECT ID,Name FROM OfficeDetail  WHERE NAME like '%$branchName%'";
                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    $pid = $p['ID'];
                                    $qry = "select m.Membercode,m.Firstname+' '+m.lastname as MemberName,
                                        m.Spousefather,m.Fatherinlaw,(l.issuedate)Date,t.loantype,
                                        l.intrate,(i.intcroption)Tnstype,h.loanheading,(l.installementno)InsNo,
                                        l.loanamount,l.instamount
					from member m, loanmain l, loantype t,intcroptionloan i,loanheading h
					where m.memberid=l.memberid and l.loantypeid=t.loantypeid and i.intcroptionid=l.intcroptionid and l.issuedate between '$cdate' and '$cdate'
					and m.officeid=l.officeid and m.officeid='$pid' and h.loanheadingid = l.loanheadingid
					order by m.membercode";
                                    $result = odbc_exec($connection, $qry);
                                    ?>
                                    <tbody style="font-size:10.5px;">
                                        <?php
                                        while ($r = odbc_fetch_array($result)) {
                                            $ln = $ln + $r['loanamount'];
                                            ?>
                                            <tr>
                                                <td><?php echo ++$counter; ?></td>
                                                <td><?php echo $r['Membercode']; ?></td>
                                                <td><?php echo $r['MemberName']; ?></td>
                                                <td><?php echo $r['Spousefather']; ?></td>
                                                <td><?php echo $r['Fatherinlaw']; ?></td>
                                                <td><?php echo $r['Date']; ?></td>
                                                <td><?php echo $r['loantype']; ?></td>
                                                <td><?php echo $r['loanheading']; ?></td>
                                                <td><?php echo $r['intrate']; ?></td>
                                                <td><?php echo $r['Tnstype']; ?></td>
                                                <td><?php echo $r['InsNo']; ?></td>
                                                <td class="text-right"><?php echo number_format($r['loanamount'], 2); ?></td>
                                                <td><?php echo $r['instamount']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot class="bg-red">
                                        <tr>
                                            <td colspan="11" class="text-bold">Total : </td>
                                            <td  ><?php echo number_format($ln, 2); ?></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                    <?php
                                } else if (isset($_POST['search'])) {
                                    $counter = 0;
                                    $ln = 0;
                                    $ID = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    $query = "select m.Membercode,m.Firstname+' '+m.lastname as MemberName,m.Spousefather,m.Fatherinlaw,
                                        (l.issuedate)Date,t.loantype,l.intrate,(i.intcroption)Tnstype,h.loanheading,(l.installementno)InsNo,
                                        l.loanamount,l.instamount
					from member m, loanmain l, loantype t,intcroptionloan i,loanheading h
					where m.memberid=l.memberid and l.loantypeid=t.loantypeid and i.intcroptionid=l.intcroptionid and l.issuedate between '$date1' and '$date2'
					and m.officeid=l.officeid and m.officeid='$ID' and h.loanheadingid = l.loanheadingid
					order by m.membercode";
                                    $results = odbc_exec($connection, $query);
                                    ?>
                                    <tbody style="font-size:10.5px;">
                                        <?php
                                        while ($r = odbc_fetch_array($results)) {
                                            $ln = $ln + $r['loanamount'];
                                            ?>
                                            <tr class="text-sm">
                                                <td><?php echo ++$counter; ?></td>
                                                <td><?php echo $r['Membercode']; ?></td>
                                                <td><?php echo $r['MemberName']; ?></td>
                                                <td><?php echo $r['Spousefather']; ?></td>
                                                <td><?php echo $r['Fatherinlaw']; ?></td>
                                                <td><?php echo $r['Date']; ?></td>
                                                <td><?php echo $r['loantype']; ?></td>
                                                <td><?php echo $r['loanheading']; ?></td>
                                                <td><?php echo $r['intrate']; ?></td>
                                                <td><?php echo $r['Tnstype']; ?></td>
                                                <td><?php echo $r['InsNo']; ?></td>
                                                <td><?php echo number_format($r['loanamount'], 2); ?></td>
                                                <td><?php echo $r['instamount']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot class="bg-red">
                                        <tr>
                                            <td colspan="11" class="text-bold">Total : </td>
                                            <td><?php echo number_format($ln, 2); ?></td>
                                            <td></td>

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
