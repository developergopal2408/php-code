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
$sqli = "SELECT ID,Name FROM FundAllow WHERE ID = '92' ";
$reso = odbc_exec($connection, $sqli);
$rowa = odbc_fetch_array($reso);
$accid = $rowa['ID'];
if (isset($_POST['checkboxes'])) {
    foreach ($_POST['checkboxes'] as $uid) {
        $bulk_option = $_POST['bulk-option'];
        if ($bulk_option == '1') {
            $bulk_author_query = "INSERT INTO SalaryAllowance(StaffID,OfficeID,AccountID,YearMonth,Remarks,PostedBy) VALUES"
                    . "('$uid','$BranchID','$accid','$fym','ThankYou','" . $_SESSION['StaffID'] . "')";
            $bulk_author_run = odbc_exec($connection, $bulk_author_query);
            echo "<script>alert('You Have Successfully Disallow the Performance of $uid');</script>";
        }
    }
}


$qry1 = "select max(dayend)date from dayend where officeid = '".$_SESSION['BranchID']."' ";
$rdate = odbc_exec($connection, $qry1);
$maxdate = odbc_fetch_array($rdate);
$dayenddate = $maxdate['date'];
$fym1 = substr($dayenddate, 0,7);
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
                    <span class="text-bold">Evaluate Performance</span>
                </div>
                <div class="box-body">
                    <h5 class="text-bold text-center"><?php echo "Performance List Of ". $fym1 ; ?></h5>
                    <table id="performance" class="table display">
                       
                        <thead class="bg-red">
                            <tr>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Staff Code</th>
                                <th>Staff Name</th>
                                <th>Position Name</th>
                                <th>Performance</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($_SESSION['BranchID'] == 1) {
                                $id = "";
                            } else {
                                $id = "and o.id = '" . $_SESSION['BranchID'] . "'";
                            }
                            $query = "select o.ID,o.Code,o.Name,s.code as scode,S.firstname+' '+s.Lastname as StaffName,s.staffid,p.PositionName
                                        from officedetail o, staffmain s, staffPosition p
                                        where o.id=s.branchid $id and p.positionid=s.positionid  and s.statusid=1 and s.groupid=1
                                        order by o.code,s.code";
                            $result = odbc_exec($connection, $query);
                            while ($row = odbc_fetch_array($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['Code']; ?></td>
                                    <td><?php echo $row['Name']; ?></td>
                                    <td><?php echo $row['scode']; ?></td>
                                    <td><?php echo $row['StaffName']; ?></td>
                                    <td><?php echo $row['PositionName']; ?></td>
                                    <td class="text-center">
                                        <?php
                                        $sql = "select * from SalaryAllowance where YearMonth = '$fym1' AND StaffID = '" . $row['staffid'] . "' AND IsAllowable = '0'";
                                        $results = odbc_exec($connection, $sql);
                                        $res = odbc_fetch_array($results);
                                        if ($res['StaffID'] == $row['staffid'] AND $_SESSION['BranchID'] > 1 AND $res['IsAllowable'] == '0') {
                                            ?>
                                            <button type="button" class="btn btn-sm bg-red" >Not Allowed</button>
                                            <?php
                                        } else if ($res['StaffID'] == $row['staffid'] AND $_SESSION['StaffID'] == '18') {
                                            ?>
                                            <button type="button" class="btn btn-sm bg-red" data-toggle = "tooltip" title="नपाउने" name="reperform" id="reperform" OnClick="reperform('<?php echo $res['ID']; ?>');" >Not Allowed</button>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="checkbox" class="checkboxes" data-toggle = "tooltip" title="नपाउने" name="performance" id="performance" OnClick="noallow('<?php echo $row['staffid']; ?>');" >
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
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    
    
    <div class="modal fade" id="pModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Want To Allow Performance?</h4>
                </div>
                <form method="POST" action="updateperformance.php" id="allows" name="allows" class="admin-form">
                    <div class="modal-body">
                        
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-group">
                            <label for="AllowPerformance" class="field prepend-icon">Remarks<em class="required">*</em></label>
                            <textarea class="form-control" id="remarks"  name="remarks" placeholder="Please Provide Remarks" required data-bv-notempty-message="Please enter your comments" title="Please Provide Remarks"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit" class="btn btn-primary pull-left"> Allow </button>
                        <a href="performance.php" class="btn btn-default" >Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="performanceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Why you want to not allow Performance?</h4>
                </div>
                <form method="POST" action="insertperformance.php" id="noallows" name="noallows" class="admin-form">
                    <div class="modal-body">
                        <input type="hidden" name="staffid" id="staffid" value="">
                        <div class="form-group">
                            <label for="Performance" class="field prepend-icon">Remarks<em class="required">*</em></label>
                            <textarea class="form-control" id="remarks"  name="remarks" placeholder="Please Provide Remarks" required data-bv-notempty-message="Please enter your comments" title="Please Provide Remarks"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit" class="btn btn-primary pull-left"> Stop Performance </button>
                        <a href="performance.php" class="btn btn-default" >Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->
<?php
include_once 'footer.php';
?>
<script>
    function noallow(staffId)
    {

        $('#staffid').val(staffId);
        if ($('input[name=performance]').is(':checked'))
        {
            //$('#getCodeModal').modal('hide');
            confirm("Are you Sure You want to DisAllow Performance ");
            $('#performanceModal').modal('show');
        }
    }
    
    function reperform(ID)
    {
       // $('#staffid').val(StaffID);
        $('#id').val(ID);
        if ($('input[name=reperform]').on(':click'))
        {
            confirm("Are you Sure You want to Allow Performance ");
            $('#pModal').modal('show');
        }
    }

    /*$(document).ready(function () {
     var remarks = $('#remarks').val();
     if (remarks != null) {
     confirm("Are you Sure You want to DisAllow Performance");
     }
     });*/

</script>
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
                message: 'Performance Report - <?php echo $fym1; ?>',
                className: 'btn btn-primary btn-xs'
            },
            {
                filename: 'Performance Report',
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                title: 'Jeevan Bikas Samaj',
                message: 'Performance Report - <?php echo $fym1; ?>',
                messageBottom: 'Approved By' ,
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
                            .addClass('display')
                            .css({
                                'font-size': '9pt',
                                'padding': '10pt'
                            });
                }

            }
        ]
    });


</script>