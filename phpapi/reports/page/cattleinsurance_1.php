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
                <small>Cattle Insurance</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Cattle Insurance</li>
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
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <a href="funddue.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                echo "<span class='text-bold text-center'>Cattle Insurance - " . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . "  )</span>";
                            }
                            ?>
                            <table id="trial" class="table table-bordered table-striped" bordered="1" style="width:auto;"> 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>SaveDate</th>
                                        <th>InsuaranceType</th>
                                        <th>CattleInsurance</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $date2 = $_POST['date2'];
                                    $date1 = $_POST['date1'];
                                    $total = 0.0;
                                    if($_SESSION['ID'] == 1){
                                        $id = "";
                                    }else{
                                        $id = "and d.officeid = '".$_SESSION['ID']."'";
                                    }
                                    
                                    if (empty($_POST)) {
                                        $qry = "select o.Code,o.Name,M.MemberCode,M.Firstname+' '+m.Lastname as MemberName ,d.savedate,T.insurancetype,
                                                (d.cramount)Cattleinsurance
                                                from officedetail o, member m, insurancetype t, insurancedetail d
                                                where o.id=m.officeid and o.id=d.officeid $id 
                                                and m.memberid=d.memberid and t.insurancetypeid=d.insurancetypeid and d.insurancetypeid=2
                                                and d.savedate between '$sdate' and '$cdate' and d.cramount>0
                                                group by o.code,o.name,m.firstname,m.lastname,d.savedate,d.cramount,m.membercode,T.insurancetype
                                                order by o.code,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['Cattleinsurance'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['Cattleinsurance']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $qry = "select o.Code,o.Name,M.MemberCode,M.Firstname+' '+m.Lastname as MemberName ,d.savedate,T.insurancetype,
                                                (d.cramount)Cattleinsurance
                                                from officedetail o, member m, insurancetype t, insurancedetail d
                                                where o.id=m.officeid and o.id=d.officeid $id and m.memberid=d.memberid 
                                                and t.insurancetypeid=d.insurancetypeid and d.insurancetypeid=2
                                                and d.savedate between '$date1' and '$date2' and d.cramount>0
                                                group by o.code,o.name,m.firstname,m.lastname,d.savedate,d.cramount,m.membercode,T.insurancetype
                                                order by o.code,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['Cattleinsurance'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['Cattleinsurance']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>

                                    <tr class="bg-red text-bold">
                                        <td colspan="6">Total</td>
                                        <td><?php echo $total; ?></td>
                                    </tr>

                                </tfoot>
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


