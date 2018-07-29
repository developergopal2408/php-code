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
                                <?php
                                if ($_SESSION['BranchID'] == 1) {
                                    ?>
                                    <!-- search form -->
                                    <form  action="" method="post" class="form-horizontal" >
                                        <div class=" form-group-sm">
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <option value="all">All Branch</option>
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
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle = "tooltip" title="Search"><i class="fa fa-search"></i></button>
                                                <a  href="dayend.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.search form -->
                                    <?php
                                }
                                ?>

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" data-toggle = "tooltip" title="Export To Xcell" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
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
                            <table id="trial" class="table table-bordered table-striped" > 
                                <thead class="bg-red"  style="width:10%;">
                                    <tr>
                                        <th>Member Code</th>
                                        <th>Member Name</th>
                                        <th>Reg Date</th>
                                        <th>Compulsory</th>
                                        <th>Last_transaction_date_of_Com</th>


                                    </tr>
                                </thead>

                                <?php
                                if (isset($_POST['search']) ) {
                                    $ID = $_POST['id'];
                                    if ($ID == "all") {
                                        $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,m.Regdate,
                                                (select sum(cramount-dramount)from savingdetail where memberid=m.memberid  and savingtypeid=2 and m.officeid=officeid)Compulsory,
                                                (select max(savedate) from savingdetail where memberid=m.memberid  and savingtypeid=2 and cramount>0 
                                                and reftype<>'Interest' )Last_transaction_date_of_Com
                                                from member m
                                                where  m.status='PASSIVE'
                                                order by m.membercode";
                                    } else {
                                        $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,m.Regdate,
                                                (select sum(cramount-dramount)from savingdetail where memberid=m.memberid and officeid='$ID' and savingtypeid=2 and m.officeid=officeid)Compulsory,
                                                (select max(savedate) from savingdetail where memberid=m.memberid and officeid='$ID' and savingtypeid=2 and cramount>0 
                                                and reftype<>'Interest' )Last_transaction_date_of_Com
                                                from member m
                                                where m.officeid='$ID' and m.status='PASSIVE'
                                                order by m.membercode";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    ?>
                                    <tbody>
                                        <?php
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr >
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['Regdate']; ?></td>
                                                <td><?php echo number_format($res['Compulsory'], 2); ?></td>
                                                <td><?php echo $res['Last_transaction_date_of_Com']; ?></td>

                                            </tr>																				
                                            <?php
                                        }
                                        ?>
                                    </tbody>

                                    <?php
                                } else if ($BranchID != 1 and empty($_POST)) {
                                    $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,m.Regdate,
                                         (select sum(cramount-dramount)from savingdetail where memberid=m.memberid and officeid='" . $_SESSION['BranchID'] . "' and savingtypeid=2 and m.officeid=officeid)Compulsory,
                                          (select max(savedate) from savingdetail where memberid=m.memberid and officeid='" . $_SESSION['BranchID'] . "' and savingtypeid=2 and cramount>0 
                                          and reftype<>'Interest' )Last_transaction_date_of_Com
                                          from member m
                                           where m.officeid='" . $_SESSION['BranchID'] . "' and m.status='PASSIVE'
                                          order by m.membercode";
                                    $result = odbc_exec($connection, $qry);
                                    ?>
                                    <tbody>
                                        <?php
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr >
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['Regdate']; ?></td>
                                                <td><?php echo number_format($res['Compulsory'], 2); ?></td>
                                                <td><?php echo $res['Last_transaction_date_of_Com']; ?></td>

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


