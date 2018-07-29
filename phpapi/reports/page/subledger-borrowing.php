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
//echo $branchName;
include_once 'header.php';

$d = date_create();
$startdate = date_create($d->format('Y-m-1'))->format('Y-m-d');

/* $lastdate = date('Y/m/d', strtotime('last day of previous month')) . "<br/>";
  $cfirstday = date('Y/m/d', strtotime("first day of this month")) . "<br/>";
  $clastday = date('Y/m/d', strtotime("last day of this month")); */

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

                <small>Sub-Ledger</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Sub-Ledger </li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Sub-Ledger</h4>
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
                                                <option value="">Select Sub-Ledger</option>
                                                <?php
                                                $query = "SELECT ID,Name,LF FROM acctree WHERE parentid = '271'";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    $msg = substr($p['Name'], strlen($p['Name']) - 3);
                                                    if (trim($msg) == $Code) {
                                                        //echo "<script>alert('".$Code."')</script>";
                                                        $selected = "selected";
                                                    } else {
                                                        $selected == "";
                                                    }
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" ><?php echo substr($p['Name'], 13); ?></option>;
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
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#voucher').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold">
                                Office: 
                                <?php
                                if (isset($_POST['id'])) {
                                    $query = "SELECT ID,Name FROM acctree  WHERE parentid = '271' AND  ID = '" . $_POST['id'] . "'";

                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="voucher" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red">
                                    <tr>

                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>VNO</th>
                                        <!--<th>VType</th>
                                        <th>Name</th>-->

                                        <th>Narration</th>
                                        <th>Dr</th>
                                        <th>Cr</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php
                                    if (empty($_POST)) {
                                        $count = 0;
                                        $bal = 0.0;


                                        $query = "SELECT ID,Name,LF FROM acctree  WHERE parentid = '271' AND NAME like '%$branchName%'";
                                        $sub = odbc_exec($connection, $query);
                                        $p = odbc_fetch_array($sub);
                                        $selectedAcc = $p['ID'];

                                        //$qry = "SELECT v.VNO,v.VType,a.Name,l.LDate,l.CrAmount,l.DrAmount,v.Narration,v.VNO FROM AccTree a INNER JOIN Ledger l ON a.ID = l.AccountHeadID INNER JOIN VoucherMaster v ON l.VNO = v.ID WHERE  (a.ParentID = 271) AND (l.LDate >= '2074/06/01') AND (l.LDate <= '2074/06/15') AND a.ID = '".$p['ID']."' ORDER BY a.ID, l.LDate ";
                                        /* $qry = "SELECT v.VNO,v.VType,a.Name,l.LDate,l.CrAmount,l.DrAmount,v.Narration FROM AccTree a 
                                          INNER JOIN Ledger l ON a.ID = l.AccountHeadID
                                          INNER JOIN VoucherMaster v ON l.VNO = v.ID
                                          WHERE (v.AdVDate >= '2017/07/17') AND (v.AdVDate <= '" . date('Y/m/d') . "')
                                          AND (a.ParentID = 271) AND a.ID = '" . $p['ID'] . "'   ORDER BY a.ID, l.LDate "; */

                                        $qry = "select '$sdate' as Date,'0B/F' as Vno,'Brought Forward' as Description, DrAmount=
                                                CASE when sum(l.dramount-l.cramount)>0 then
                                                sum(l.dramount-l.cramount) 
                                               else 0 end,CrAmount=CASE when 
                                               sum(l.dramount-l.cramount)<0 then
                                               sum(l.dramount-l.cramount) 
                                               else 0 end
                                               from acctree a,ledger l
                                               where a.id=l.accountheadid and l.ldate>='2074/04/01' and l.ldate < '$sdate' and a.parentid=271
                                               and a.id='$selectedAcc'
                                               union all
                                               select l.ldate as Date,CAST(v.Vno AS varchar(5)) as Vno,v.Narration as Description,l.dramount as DrAmount,l.cramount as CrAmount
                                               from acctree a,ledger l,vouchermaster v
                                               where a.id=l.accountheadid and l.ldate >='$sdate' and l.ldate <= '$cdate' and a.parentid=271
                                               and v.id=l.vno and a.id='$selectedAcc'
                                               order by Date,Vno";
                                        $result = odbc_exec($connection, $qry);

                                        while ($res = odbc_fetch_array($result)) {

                                            $bal = $bal + ($res['DrAmount'] - $res['CrAmount']);
                                            ?>
                                            <tr>
                                                <td><?php echo ++$count; ?></td>
                                                <td><?php echo $res['Date']; ?></td>
                                                <td><?php echo $res['Vno']; ?></td>
                                                <td><?php echo $res['Description']; ?></td>
                                                <td class="text-right"><?php echo number_format($res['DrAmount'], 2); ?></td>
                                                <td class="text-right"><?php echo number_format($res['CrAmount'], 2); ?></td>
                                                <td class="text-right"><?php echo number_format($bal, 2); ?></td>
                                                <td>
                                                    <?php
                                                    if ($count != 1) {
                                                        ?>
                                                        <a href="vdetail.php?Vno=<?php echo $res['Vno']; ?>&Date=<?php echo $res['Date']; ?>" target="_blank"  class="btn btn-sm bg-blue">View Details</a>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>




                                            </tr>

                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $ID = $_POST['id'];
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        $selectedAcc = $ID;

                                        /* $qry = "SELECT v.ID,v.VNO,v.VType,a.Name,l.LDate,l.CrAmount,l.DrAmount,v.Narration FROM AccTree a 
                                          INNER JOIN Ledger l ON a.ID = l.AccountHeadID
                                          INNER JOIN VoucherMaster v ON l.VNO = v.ID
                                          WHERE (l.LDate >= '$date1') AND (l.LDate <= '$date2')
                                          AND (a.ParentID = 271) AND a.ID = '$ID' ORDER BY a.ID, l.LDate ";
                                          //$qry = "select o.code,o.name, v.VNO,v.VType,l.ldate,a.name,l.Particulars,v.narration,sum(l.dramount)as DR,sum(l.cramount)as CR from acctree a,ledger l,vouchermaster v,officedetail o where o.id=l.officeid And a.id=l.accountheadid and l.ldate>='$date1' and l.ldate<='$date2' and a.id='$ID' and v.id=l.vno group by o.code,o.name,l.VNO, v.VType ,a.id,a.name,l.ldate,l.Particulars,v.narration,v.VNO order by a.id,l.ldate"; */

                                        $qry = "select '$date1' as Date,'0B/F' as Vno,'Brought Forward' as Description, DrAmount=
                                                CASE when sum(l.dramount-l.cramount)>0 then
                                                sum(l.dramount-l.cramount) 
                                               else 0 end,CrAmount=CASE when 
                                               sum(l.dramount-l.cramount)<0 then
                                               sum(l.dramount-l.cramount) 
                                               else 0 end
                                               from acctree a,ledger l
                                               where a.id=l.accountheadid and l.ldate>='2074/04/01' and l.ldate<'$date1' and a.parentid=271
                                               and a.id='$ID'

                                               union all

                                               select l.ldate as Date,CAST(v.Vno AS varchar(5)) as Vno,v.Narration as Description,l.dramount as DrAmount,l.cramount as CrAmount
                                               from acctree a,ledger l,vouchermaster v
                                               where a.id=l.accountheadid and l.ldate>='$date1' and l.ldate <= '$date2' and a.parentid=271
                                               and v.id=l.vno and a.id=' $ID'
                                               order by Vno,Date";

                                        $result = odbc_exec($connection, $qry);
                                        //$numrow = odbc_result($result,20);
                                        $bal = 0;
                                        $counter = 0;
                                        while ($res = odbc_fetch_array($result)) {

                                            $bal = $bal + ($res['DrAmount'] - $res['CrAmount']);
                                            ?>
                                            <tr >
                                                <td><?php echo ++$counter; ?></td>
                                                <td><?php echo $res['Date']; ?></td>
                                                <td><?php echo $res['Vno']; ?></td>
                                                <td><?php echo $res['Description']; ?></td>
                                                <td class="text-right"><?php echo number_format($res['DrAmount'], 2); ?></td>
                                                <td class="text-right"><?php echo number_format($res['CrAmount'], 2); ?></td>
                                                <td class="text-right"><?php echo number_format($bal, 2); ?></td>
                                                <td>
                                                    <?php
                                                    if ($counter != 1) {
                                                        ?>
                                                        <a href="vdetail.php?Vno=<?php echo $res['Vno']; ?>&Date=<?php echo $res['Date']; ?>" class="btn btn-sm bg-blue" target="_blank">View Details</a>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>

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
