<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 180);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(180);
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
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
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
                <small>Main Ledger</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Main Ledger </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Main Ledger
                                <button id="excel" class="btn bg-blue pull-right" title="Export to Excel" href="#" onClick ="$('#submain').tableExport({type: 'excel', escape: 'false'});"><i class="glyphicon glyphicon-export"></i></button>
                            </h4>
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
                                                <option value="">Select Head</option>
                                                <?php
                                                $query = "SELECT ID, ParentID, Name FROM AccTree WHERE State='1' AND  ID In(SELECT ParentID FROM AccTree ) AND ID != '1'";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" ><?php echo $p['Name']; ?></option>;
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                        if ($_SESSION['ID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="branchid" id="branchid" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $query = "SELECT ID,Name FROM officedetail order by ID ASC";
                                                    $sub = odbc_exec($connection, $query);
                                                    while ($p = odbc_fetch_array($sub)) {
                                                        ?>
                                                        <option value="<?php echo $p['ID']; ?>" ><?php echo $p['Name']; ?></option>;
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
                                            <a href="sub-mainledger.php"  class=" btn btn-flat bg-blue"><i class="fa fa-refresh"></i></a>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                </div>

                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['branchid']) AND isset($_POST['id'])) {
                                    $query = "SELECT ID,Name FROM officedetail  WHERE  ID = '" . $_POST['branchid'] . "'";
                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
									$query2 = "SELECT Name FROM AccTree WHERE State='1' AND ID = '".$_POST['id']."'";
                                    $sub2 = odbc_exec($connection, $query2);
									$q = odbc_fetch_array($sub2);
                                    echo $p['Name']." - ".$q['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="submain"  class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red">
                                    <tr>
                                        <th>LF</th>
                                        <th>Name</th>
										 
                                        <th>DrAmount</th>                             
                                        <th>CrAmount</th>
										<th>Balance</th>
										<th>Dr/Cr</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ((isset($_POST['search']) AND ( $_SESSION['ID'] > 1))) {
                                        $ID = $_POST['id'];
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        $counter = 0;
                                        $bal = 0.0;
                                        $qry = "select a.LF, a.Name,sum(DrAmount) DrAmount,sum(CrAmount) CrAmount,sum(DrAmount - CrAmount) Balance
                                                FROM Ledger l ,Acctree a
                                                WHERE l.AccountHeadID=a.ID
                                                and ldate between '$date1' and '$date2' 
                                                and a.parentid = '$ID'
                                                and l.officeid='" . $_SESSION['ID'] . "'
                                                GROUP BY a.LF, a.Name";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $bal = $res['Balance'];
                                            ?>
                                            <tr>

                                                <td><?php echo $res['LF']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
												
                                                <td class="text-right"><?php echo number_format($res['DrAmount'],2); ?></td>
                                                <td class="text-right"><?php echo number_format($res['CrAmount'],2); ?></td>
												<td class="text-right"><?php echo number_format(abs($bal),2); ?></td>
												<td><?php if($bal > 0){
													echo "Dr";
												}else{
													echo "Cr";
												} ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else if ((isset($_POST['search']) AND ( $_SESSION['ID'] == 1))) {
                                        $ID = $_POST['id'];
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        $counter = 0;
                                        $bal = 0.0;
                                        $qry = "select a.LF, a.Name,sum(DrAmount) DrAmount,sum(CrAmount) CrAmount,sum(DrAmount - CrAmount) Balance
                                                FROM Ledger l ,Acctree a
                                                WHERE l.AccountHeadID=a.ID
                                                and ldate between '$date1' and '$date2' 
                                                and a.parentid = '$ID'
                                                and l.officeid='" . $_POST['branchid'] . "'
                                                GROUP BY a.LF, a.Name";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
											 $bal = $res['Balance'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['LF']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
												
                                                <td class="text-right"><?php echo number_format($res['DrAmount'],2); ?></td>
                                                <td class="text-right"><?php echo number_format($res['CrAmount'],2); ?></td>
												<td class="text-right"><?php echo number_format(abs($bal),2); ?></td>
												<td><?php if($bal > 0){
													echo "Dr";
												}else{
													echo "Cr";
												} ?></td>
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
<script>
    $('#submain').dataTable({
        "searching": false,
        "scrollY": "300px",
        "scrollCollapse": true,
        "paging": false,
        "columnDefs": [
            {"width": "20%", "targets": 0},
            {"width": "20%", "targets": 1},
            {"width": "20%", "targets": 2},
            {"width": "20%", "targets": 3}
        ]
    });
</script>