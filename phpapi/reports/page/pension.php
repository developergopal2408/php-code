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

                <small>Pension Card List</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Pension Card List</li>
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
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle="tool-tip" title="Search"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <a href="pension.php" class="btn btn-flat bg-blue" data-toggle="tool-tip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['id'])) {
                                    $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";

                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="pension" class="table table-bordered table-striped" bordered="1"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>Member Code</th>
                                        <th>Member NAME</th>
                                        <th>DOB</th>
                                        <th>CentreName</th>
                                        <th>Address</th>
                                        <th>RegDate</th>
                                        <th>Print</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($_POST)) {

                                        $qry = "select m.memberid,m.officeid,m.membercode,m.firstname+' '+m.Lastname as MemberName,m.DOB,(select centername from centermain where centerid=m.centerid and officeid=m.officeid)centername,m.spouseFather,m.Fatherinlaw,v.vdcname+' '+m.wardno as Address ,m.regdate,m.photo
                                                from member m, vdc v
                                                where m.vdcid=v.vdcid
                                                 and m.officeid='" . $_SESSION['ID'] . "' and m.status='active'
                                                group by m.memberid,m.membercode,m.firstname,m.Lastname ,m.DOB,m.spouseFather,m.Fatherinlaw,m.wardno ,m.regdate,m.photo,m.centerid,m.officeid,v.vdcname
                                                order by m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        if (odbc_num_rows($result) > 0) {
                                            while ($res = odbc_fetch_array($result)) {
                                                $id = $res['officeid'];
                                                $mid = $res['memberid'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $res['membercode']; ?></td>
                                                    <td><?php echo $res['MemberName']; ?></td>
                                                    <td><?php echo $res['DOB']; ?></td>
                                                    <td><?php echo $res['centername']; ?></td>
                                                    <td><?php echo $res['Address']; ?></td>
                                                    <td><?php echo $res['regdate']; ?></td>
                                                    <td>
                                                        <a href="pensionpdf.php?mid=<?php echo $mid; ?>&officeid=<?php echo $id; ?>" target="_new" class="btn btn-flat bg-red"><i class="glyphicon glyphicon-print"></i></a>
                                                    </td>

                                                </tr>
                                                <?php
                                            }
                                        }
                                    } else if (isset($_POST['search'])) {

                                        $ID = $_POST['id'];

                                        $qry = "select m.memberid,m.officeid,m.membercode,m.firstname+' '+m.Lastname as MemberName,m.DOB,(select centername from centermain where centerid=m.centerid and officeid=m.officeid)centername,m.spouseFather,m.Fatherinlaw,v.vdcname+' '+m.wardno as Address ,m.regdate,m.photo
                                                from member m, vdc v
                                                where m.vdcid=v.vdcid
                                                 and m.officeid='$ID' and m.status='active'
                                                group by m.memberid,m.membercode,m.firstname,m.Lastname ,m.DOB,m.spouseFather,m.Fatherinlaw,m.wardno ,m.regdate,m.photo,m.centerid,m.officeid,v.vdcname
                                                order by m.membercode";
                                        $result = odbc_exec($connection, $qry);

                                        while ($res = odbc_fetch_array($result)) {
                                            $id = $res['officeid'];
                                            $mid = $res['memberid'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['DOB']; ?></td>
                                                <td><?php echo $res['centername']; ?></td>
                                                <td><?php echo $res['Address']; ?></td>
                                                <td><?php echo $res['regdate']; ?></td>
                                                <td>
                                                    <a href="pensionpdf.php?mid=<?php echo $mid; ?>&officeid=<?php echo $id; ?>" target="_new" class="btn btn-flat bg-red"><i class="glyphicon glyphicon-print"></i></a>
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
	

