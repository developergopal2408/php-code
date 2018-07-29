<?php
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

//$sdate = $ndate['year'] . "/" . '04' . "/" . '01';
$sdate = "2074/04/01";
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

                <small>Branch Trial</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Branch Trial</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Branch Trial</h4>
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">
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
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
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
                            <div class="box-title with-header text-bold text-center">
                                Office : 
                                <?php
                                if (isset($_POST['id'])) {
                                    $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";

                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $_POST['date2']." - ".$p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="trial" class="table table-bordered table-striped" bordered="1"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>LF</th>
                                        <th>NAME</th>
                                        <th>DEBIT</th>
                                        <th>CREDIT</th>

                                    </tr>
                                </thead>
                               
                                    <?php
                                    if (empty($_POST)) {
                                        $granddr = 0.0;
                                        $grandcr = 0.0;
                                        $qry = "select a.LF,a.Name,'',
                                                (select sum(dramount-cramount)from ledger where ldate between'$sdate' and '$cdate' and a.groupid in(3,4)and accountheadid=a.id and officeid='" . $_SESSION['ID'] . "')Dr,
                                                (select sum(cramount-dramount)from ledger where ldate between'$sdate' and '$cdate' and a.groupid in(2,5)and accountheadid=a.id and officeid='" . $_SESSION['ID'] . "')Cr
                                                from acctree a,ledger l
                                                where a.id=l.accountheadid 
                                                group by a.lf,a.name ,a.groupid,a.id
                                                order by a.lf";
                                        $result = odbc_exec($connection, $qry);
										?>
										 <tbody>
										<?php
                                        if (odbc_num_rows($result) > 0) {
                                            while ($res = odbc_fetch_array($result)) {
                                                if (!empty($res['Cr']) or ! empty($res['Dr'])) {
                                                    $granddr += $res['Dr'];
                                                    $grandcr += $res['Cr'];
                                                    ?>
                                                    <tr>

                                                        <td><?php echo $res['LF']; ?></td>
                                                        <td><?php echo $res['Name']; ?></td>
                                                        <td><?php echo number_format($res['Dr'],2); ?></td>
                                                        <td><?php echo number_format($res['Cr'],2); ?></td>
                                                    </tr>




                                                    <?php
                                                }
                                            }
                                            ?>
										</tbody>
										<tfoot class="bg-red">
                                            <tr>

                                                <td colspan=2 class="text-bold">Total : </td>
                                               
                                                <td class="text-bold "><?php echo number_format($granddr,2); ?></td>
                                                <td class="text-bold "><?php echo number_format($grandcr,2); ?></td>

                                            </tr>
											
											</tfoot>
                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $ID = $_POST['id'];
                                        $date2 = $_POST['date2'];
                                        $qry = "select a.LF,a.Name,'',
                                                (select sum(dramount-cramount)from ledger where ldate between '$sdate' and '$date2' and a.groupid in(3,4)and accountheadid=a.id and officeid='$ID')Dr,
                                                (select sum(cramount-dramount)from ledger where ldate between '$sdate' and '$date2' and a.groupid in(2,5)and accountheadid=a.id and officeid='$ID')Cr
                                                from acctree a,ledger l
                                                where a.id=l.accountheadid 
                                                group by a.lf,a.name ,a.groupid,a.id
                                                order by a.lf";
                                        $result = odbc_exec($connection, $qry);
                                        $tcr = 0.0;
                                        $tdr = 0.0;
										?>
										<tbody>
										<?php
                                        while ($res = odbc_fetch_array($result)) {
                                            $tdr += $res['Dr'];
                                            $tcr += $res['Cr'];
                                            if (!empty($res['Cr']) or ! empty($res['Dr'])) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $res['LF']; ?></td>
                                                    <td><?php echo $res['Name']; ?></td>
                                                    <td><?php echo number_format($res['Dr'],2); ?></td>
                                                    <td><?php echo number_format($res['Cr'],2); ?></td>
                                                </tr>																				
                                                <?php
                                            }
                                        }
                                        ?>
										</tbody>
										<tfoot class="bg-red">
                                        <tr>
                                            <td colspan=2 class="text-bold">Total : </td>                                           
                                            <td class="text-bold"><?php echo number_format($tdr,2); ?></td>
                                            <td class="text-bold"><?php echo number_format($tcr,2); ?></td>

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
	

