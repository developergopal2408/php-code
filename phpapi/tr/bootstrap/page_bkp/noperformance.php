<?php
/*
ini_set('session.gc_maxlifetime', 180);
session_set_cookie_params(180);
ini_set('max_execution_time', 300);
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}*/
include_once 'top.php';
include_once 'header.php';
$qry1 = "select max(dayend)date from dayend where officeid = '".$_SESSION['BranchID']."' ";
$rdate = odbc_exec($connection, $qry1);
$maxdate = odbc_fetch_array($rdate);
$dayenddate = $maxdate['date'];
$fym1 = substr($dayenddate, 0,7);
$sqli = "SELECT ID,Name FROM FundAllow WHERE ID = '92' ";
$reso = odbc_exec($connection, $sqli);
$rowa = odbc_fetch_array($reso);
$accid = $rowa['ID'];
if (isset($_POST['checkboxes'])) {
    foreach ($_POST['checkboxes'] as $uid) {
        $bulk_option = $_POST['bulk-option'];
        if ($bulk_option == '1') {
            $bulk_author_query = "UPDATE SalaryAllowance SET IsChecked = '1',IsCheckedBy = '" . $_SESSION['StaffID'] . "' WHERE ID = '$uid'";
            $bulk_author_run = odbc_exec($connection, $bulk_author_query);
            echo "<script>alert('You Have Successfully Disallow the Performance');window.location='noperformance.php'</script>";
        }
    }
}

?>
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-dashboard"></i> <?php echo $branchName; ?>
                <small>Performance Board</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Performance</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-solid">
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
                        <a href="noperformance.php"  class=" btn btn-flat bg-blue pull-right" title="Refresh"><i class="fa fa-refresh"></i></a>
                    </div>
                </div>
                <div class="box-body">
                    <form class="form-inline" action="" method="post">
                        <div class="form-group">
                            <select name="bulk-option" id="" class="form-control select2 " style="width:200px;">
                                <option value="select">Select Option</option>
                                <option value="1">नपाउने</option>
                            </select>
                            <input type="submit" class="btn btn-success btn-sm " value="Post IT">
                        </div>
                        <?php
                        if (isset($_POST['search'])) {
                            $date1 = $_POST['date1'];
                            $fdate = substr($date1, 0, 7);
                            $date2 = $_POST['date2'];
                            $tdate = substr($date2, 0, 7);
                            echo "<span class='text-red text-bold pull-right'>Not Allowed Performance Result From ( " . $fdate . " - " . $tdate . ")</span>";
                        }else{
                            echo "<span class='text-red text-bold pull-right'>Performance Not Allowed List of ". $fym ."</span>";
                        }
                        ?>
                        <hr>
                        <table id="performance" class="table table-bordered">
                            <thead class="bg-red">
                                <tr>
                                    <th>Branch Code</th>
                                    <th>Branch Name</th>
                                    <th>Staff Code</th>
                                    <th>Staff Name</th>
                                    <th>Position Name</th>
                                    <?php
                                    if (isset($_POST['search'])) {
                                        ?>
                                        <th>Performance</th>
                                        <?php
                                    } else {
                                        ?>
                                        <th>Performance &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" id="selectallboxes" data-toggle = "tooltip" title="नपाउने"></th>
                                        <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $date1 = $_POST['date1'];
                                $fdate = substr($date1, 0, 7);
                                $date2 = $_POST['date2'];
                                $tdate = substr($date2, 0, 7);
                                if (isset($_POST['search'])) {
                                    $query = "select * from SalaryAllowance where YearMonth BETWEEN '$fdate' AND '$tdate' AND IsAllowable IN ('0','1')  AND IsChecked = '1'";
                                } else {
                                    $query = "select * from SalaryAllowance where YearMonth = '$fym1' AND IsAllowable = '0' AND IsChecked = '0'";
                                }
                                $result = odbc_exec($connection, $query);
                                while ($row = odbc_fetch_array($result)) {
                                    $sid = $row['ID'];
                                    $sql1 = "select * from StaffMain where StaffID = '" . $row['StaffID'] . "'";
                                    $result1 = odbc_exec($connection, $sql1);
                                    $rows = odbc_fetch_array($result1);
                                    $scode = $rows['Code'];
                                    $pid = $rows['PositionID'];
                                    $fname = $rows['FirstName'];
                                    $lname = $rows['LastName'];
                                    $sname = $fname . " " . $lname;

                                    $sql2 = "select * from OfficeDetail where ID = '" . $row['OfficeID'] . "'";
                                    $result2 = odbc_exec($connection, $sql2);
                                    $rowo = odbc_fetch_array($result2);
                                    $ocode = $rowo['Code'];
                                    $oname = $rowo['Name'];

                                    $sql3 = "select * from StaffPosition where PositionId = '$pid'";
                                    $result3 = odbc_exec($connection, $sql3);
                                    $rowp = odbc_fetch_array($result3);
                                    $pname = $rowp['PositionName'];
                                    ?>
                                    <tr>
                                        <td><?php echo $ocode; ?></td>
                                        <td><?php echo $oname; ?></td>
                                        <td><?php echo $scode; ?></td>
                                        <td><?php echo $sname; ?></td>
                                        <td><?php echo $pname; ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($row['IsChecked'] == '1') {
                                                echo "<span class='label label-danger'>Not Allowed</span>";
                                            } else {
                                                ?>
                                                <input type="checkbox" class="checkboxes" data-toggle = "tooltip" title="नपाउने" name="checkboxes[]" value="<?php echo $sid; ?>" >
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </form>
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
<script>
    $(document).ready(function () {
        $('#selectallboxes').click(function (event) {
            if (this.checked) {
                $('.checkboxes').each(function () {
                    this.checked = true;
                });
            } else {
                $('.checkboxes').each(function () {
                    this.checked = false;
                });
            }
        });
    });</script>
<script>
    $('#performance').DataTable({
        "order": [[0, "desc"]],
        "scrollY": "275px",
        "paging": false,
        dom: 'Bfrtip',
        buttons: [
            {
                filename: 'Performance Report',
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i>',
                title: 'Jeevan Bikas Samaj',
                message: 'Performance Report',
                className: 'btn btn-primary btn-xs'
            },
            {
                filename: 'Performance Report',
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                title: 'Jeevan Bikas Samaj',
                message: 'Performance Report of <?php echo $fym1; ?>',
                messageBottom: '<span class="pull-right text-bold" style="margin:25px;"> Checked By <br/><br/>---------------------</span>',
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '10pt',
                                'text-align': 'center'
                            })
                            .prepend(
                                    '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                                    );
                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css({
                                'font-size': '9pt',
                                'padding': '10pt'
                            });
                }

            }
        ]
    });


</script>