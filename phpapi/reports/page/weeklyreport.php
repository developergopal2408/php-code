<?php
ini_set('session.gc_maxlifetime', 180);
session_set_cookie_params(180);
ini_set('max_execution_time', 500);
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
include_once 'sidebar_header.php';
include_once 'sidebar.php';
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
include_once 'header.php';
$monday = strtotime("last Monday");
$monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
$sunday = strtotime(date("Y/m/d", $monday) . " +6 days");
$startweek = date("Y/m/d", $monday);
$endweek = date("d", $sunday);
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', $startweek);
$nepdate = $cal->eng_to_nep($year, $month, $day);
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;
$ndate = $cal->eng_to_nep($year, $month, $endweek);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . $ndate['date'];
//$results = "Current week range from $cdate to $sdate ";
?>

<!-- Site wrapper -->
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>
                <small>Weekly Report</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Weekly Report</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Weekly Report</h4>
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date"  value="<?php
                                            if (isset($_POST['date1'])) {
                                                echo $_POST['date1'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date2'])) {
                                                echo $_POST['date2'];
                                            } else {
                                                echo $sdate;
                                            }
                                            ?>" >
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
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#daybook').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['id'])) {
                                    $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo "Weekly Report Of " . $p['Name'] . " From ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )";
                                } else {
                                    echo "Weekly Report Of " . $branchName . " From ( " . $cdate . " - " . $sdate . " )";
                                }
                                ?>
                            </div>

                            <table id="daybook"  class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        
                                        <th>Name</th>
                                        <th>NewaddMember</th>
                                        <th>Dropout</th>
                                        <th>Lprogress</th>
                                        <th>savingincre</th>
                                        <th>penprovisionincre</th>
                                        <th>Bankbal</th>
                                        <th>cashbal</th>
                                        <th>HObal</th>
                                        <th>RemitBal</th>
                                    </tr>
                                </thead>
                                <tbody> 

                                    <?php
                                    if (empty($_POST)) {
                                        $qry = "select o.Code,o.Name,
                                        (select count(memberid) from member where o.id=officeid and regdate between'$cdate' and '$sdate' and status='ACTIVE')NewaddMember,
                                        (select count(memberid) from member where o.id=officeid and Dropoutdate between'$cdate' and '$sdate' and status='DROPOUT')Dropout,
                                        (select sum(loandr-loancr) from loandetail where o.id=officeid and savedate between'$cdate' and '$sdate' )Lprogress,
                                        (select sum(cramount-dramount)from ledger where o.id=officeid and ldate between'$cdate' and '$sdate' and accountheadid in(select id from acctree
                                        where parentid=313)group by id)savingincre,
                                        (select sum(cramount-dramount)from ledger where o.id=officeid and ldate between'$cdate' and '$sdate' and accountheadid=455)penprovisionincre,
                                        (select sum(dramount-cramount)from ledger where o.id=officeid and ldate between '2074/04/01'and  '$sdate' and accountheadid in(select id from acctree where parentid in(306,307,308) group by id))Bankbal,
                                        (select sum(dramount-cramount)from ledger where o.id=officeid and ldate between '2074/04/01' and '$sdate' and accountheadid=301)cashbal,
                                        (select sum(cramount-dramount)from ledger where o.id=officeid and ldate between '2074/04/01' and '$sdate' and accountheadid=502)HObal,
                                        (select sum(dramount-cramount)from ledger where o.id=officeid and ldate between '2074/04/01' and '$sdate' and accountheadid=258)RemitBal
                                        from officedetail o
                                        group by o.code,o.name,o.id
                                        order by o.code";
                                        $result = odbc_exec($connection, $qry);
                                        while ($r = odbc_fetch_array($result)) {
                                            ?>
                                            <tr class="text-sm">
                                                
                                                <td><?php echo $r['Name']; ?></td>
                                                <td><?php echo $r['NewaddMember']; ?></td>
                                                <td><?php echo $r['Dropout']; ?></td>
                                                <td><?php echo $r['Lprogress']; ?></td>
                                                <td><?php echo $r['savingincre']; ?></td>
                                                <td><?php echo $r['penprovisionincre']; ?></td>
                                                <td><?php echo $r['Bankbal']; ?></td>
                                                <td><?php echo $r['cashbal']; ?></td>
                                                <td><?php echo $r['HObal']; ?></td>
                                                <td><?php echo $r['RemitBal']; ?></td>
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
