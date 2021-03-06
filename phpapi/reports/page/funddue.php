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
                <small>Fund Due</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Fund Due</li>
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
                                $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                                $results = odbc_exec($connection, $sqls);
                                $reso = odbc_fetch_array($results);

                                echo "<span class='text-bold text-center'>Fund Due - " . $reso['Name'] . "( " . $_POST['date1'] . "  )</span>";
                            }
                            ?>
                            <table id="trial" class="table table-bordered table-striped" bordered="1" style="width:auto;"> 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Name</th>
                                        <th>MemberID</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>InsuaranceType</th>
                                        <th>SaveDate</th>
                                        <th>PreBal</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    $total = 0.0;
                                    if ($id == '') {
                                        $qry = "select m.memberid,m.memberid, m.membercode, m.firstname+' '+m.LastName as MemberName, i.insurancetype, a.savedate, 
                                                sum(a.prebal)prebal,(select name from officedetail where ID = m.officeid)name
                                                from member m, insurancedetail a, insurancetype i
                                                where m.officeid=a.officeid and m.memberid=a.memberid  and i.insurancetypeid=a.insurancetypeid  
                                                and a.savedate = '$date1'  
                                                group by m.memberid, m.membercode,m.firstname,m.lastname,a.savedate, i.insurancetype,m.officeid
                                                having sum(a.prebal)>0 order by a.savedate,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['prebal'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['name']; ?></td>
                                                <td><?php echo $res['memberid']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['prebal']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $qry = "select m.memberid,m.memberid, m.membercode, m.firstname+' '+m.LastName as MemberName, i.insurancetype, a.savedate, 
                                                sum(a.prebal)prebal,(select name from officedetail where ID = m.officeid)name
                                                from member m, insurancedetail a, insurancetype i
                                                where m.officeid=a.officeid and m.memberid=a.memberid  and i.insurancetypeid=a.insurancetypeid  
                                                and a.savedate = '$date1'  and a.officeid = '$id'
                                                group by m.memberid, m.membercode,m.firstname,m.lastname,a.savedate, i.insurancetype,m.officeid
                                                having sum(a.prebal)>0 order by a.savedate,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['prebal'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['name']; ?></td>
                                                <td><?php echo $res['memberid']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['prebal']; ?></td>
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


