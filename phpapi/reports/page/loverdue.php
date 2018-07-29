<?php
include_once 'topa.php';
include_once 'header.php';
?>
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';

    function arrayContainsDuplicate($array) {
        return count($array) != count(array_unique($array));
    }
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> 
                <small>Loan OverDue</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan OverDue</li>
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
                                            <input type="date" name="date" id="date" class="form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date'])) {
                                                       echo $_POST['date'];
                                                   } else {
                                                       echo date('Y-m-d');
                                                   }
                                                   ?>"
                                                   >
                                        </div>

                                        <?php
                                        if ($BranchID == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="oid" id="oid" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail WHERE ID > 1 ORDER BY ID ASC ";
                                                    $result = sqlsrv_query($connection, $sql1);

                                                    while ($rows = sqlsrv_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" <?php
                                                        if ($_POST['oid'] == $rows['ID']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
                                                                <?php
                                                            }
                                                            ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-3">
                                                <select name="arrear" id="arrear" class="form-control select2" required>
                                                    <option value="0">Select Arrear Days</option>
                                                    <option value="15">Above 15 Days</option>
                                                    <option value="30">Above 30 Days</option>
                                                    <option value="45">Above 45 Days</option>
                                                </select>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <a href="loverdue.php"  class=" btn btn-flat bg-blue pull-right" title="Refresh"><i class="fa fa-refresh"></i></a>


                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                                $results = sqlsrv_query($connection, $sqls);
                                $reso = sqlsrv_fetch_array($results);
                                echo "<h5 class='text-bold text-center'>Loan OverDue - " . $reso['Name'] . "( " . $_POST['date'] . "  )</h5>";
                            }
                            ?>
                            <table id="loandue1" class="table stripe row-border order-column text-sm" cellspacing="0" width="100%"> 
                                <thead class="bg-red">
                                    <tr>
                                        <th>Off.ID</th>
                                        <th>LM.ID</th>
                                        <th>MemID</th>
                                        <th>Mem.Name</th>
                                        <th>LH.ID</th>
                                        <th>L.No</th>
                                        <th>LDate</th>
                                        <th>M.Date</th>
                                        <th>L.Amt</th>
                                        <th>P.Amt</th>
                                        <th>Arr.Days</th>
                                        <th>PriPaid</th>
                                        <th>IntPaid</th>
                                        <th>Sch.Pri</th>
                                        <th>Sch.Int</th>
                                        <th>Sch.Date</th>
                                        <th>Sch.Cnt</th>
                                        <th>B.Amt</th>
                                        <th>LP.Date</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['oid'];
                                    $date = $_POST['date'];
                                    $arrear = $_POST['arrear'];
                                    if (isset($_POST['search'])) {
                                        $qry = "DECLARE @p_date date = '$date'
                                    DECLARE @p_officeid int = '$id'
                                    --DECLARE @p_loanmainid int = 833
                                    --SELECT * FROM (
                                    SELECT --TOP 10 
                                    lm.OfficeID, lm.LoanMainID, lm.MemberID
                                    , mm.FirstName+' '+mm.LastName AS MemberName
                                    --, mm.CenterID, mm.GroupID
                                    , lm.LoanHeadingID, lm.LoanNo
                                    , lm.IssueDate AS LoanDate, lm.IssueDateAD, lm.MaturityDate, lm.LoanAmount, lm.PaidAmount
                                    , dbo.GetArrearsDaysAD(lm.OfficeID, lm.LoanMainID, @p_date) AS ArrearsDays
                                    , ld.PriPaid, ld.IntPaid
                                    , ls.SchedulePri, ls.ScheduleInt, ls.ScheduleDate, ScheduleCnt
                                    , lm.BalanceAmount, lm.LastPaidDate, lm.LastPaidDateAD
                                      FROM LoanMain AS lm, Member mm,
                                            (SELECT ls.OfficeID, ls.LoanMainID, MIN(PaymentDate) AS ScheduleDate, 
                                                            ISNULL(SUM(ls.PriAmt),0) AS SchedulePri, ISNULL(SUM(ls.IntAmt),0) AS ScheduleInt, ISNULL(COUNT(1),0) ScheduleCnt
                                              FROM MemberLoanSchedule AS ls
                                             WHERE ls.OfficeID = @p_officeid
                                               --AND ls.LoanMainID = @p_loanmainid
                                               AND ls.PaymentDateAD <= @p_date
                                               AND ls.IsSkip = 0
                                             GROUP BY ls.OfficeID, ls.LoanMainID) AS ls, 
                                        (SELECT ld.OfficeID, ld.LoanMainID, ISNULL(SUM(ld.LoanCr),0) AS PriPaid, ISNULL(SUM(ld.IntCr),0) AS IntPaid
                                              FROM LoanDetail AS ld
                                             WHERE ld.OfficeID=@p_officeid
                                               AND (ld.LoanCr > 0 OR ld.IntCr > 0)
                                               AND ISNULL(ld.SaveDateAD, dbo.GetEngDate(ld.SaveDate)) <= @p_date
                                             GROUP BY ld.OfficeID, ld.LoanMainID) AS ld
                                     WHERE lm.OfficeID = @p_officeid --and MemberID=1 
                                     --AND lm.LoanMainID = @p_loanmainid
                                     --AND lm.PaidAmount < lm.LoanAmount
                                     AND ISNULL(lm.IssueDateAD, dbo.GetEngDate(lm.IssueDateAD)) <= @p_date --AND lm.IssueDateAD >= '2016-01-01'
                                     AND lm.OfficeID = ls.OfficeID
                                     AND lm.LoanMainID = ls.LoanMainID
                                     AND lm.OfficeID = ld.OfficeID
                                     AND lm.LoanMainID = ld.LoanMainID
                                     AND lm.OfficeID = mm.OfficeID
                                     AND lm.MemberID = mm.MemberID
                                     --AND ld.PriPaid < ls.SchedulePri
                                     --AND ls.ScheduleCnt > 0
                                     AND lm.BalanceAmount>0
                                     --) AS x
                                     --WHERE x.PriPaid<x.SchedulePri

                                    --select dbo.GetEngDate('2074-12-29'); ";
                                    }
                                    $result = sqlsrv_query($connection, $qry);

                                    while ($res = sqlsrv_fetch_array($result)) {
                                        if ($res['ArrearsDays'] >= $arrear) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['OfficeID']; ?></td>
                                                <td><?php echo $res['LoanMainID']; ?></td>
                                                <td><?php echo $res['MemberID']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['LoanHeadingID']; ?></td>
                                                <td><?php echo $res['LoanNo']; ?></td>
                                                <td><?php echo $res['LoanDate']; ?></td>
                                                <td><?php echo $res['MaturityDate']; ?></td>
                                                <td><?php echo $res['LoanAmount']; ?></td>
                                                <td><?php echo $res['PaidAmount']; ?></td>
                                                <td><?php echo $res['ArrearsDays']; ?></td>
                                                <td><?php echo $res['PriPaid']; ?></td>
                                                <td><?php echo $res['IntPaid']; ?></td>
                                                <td><?php echo $res['SchedulePri']; ?></td>
                                                <td><?php echo $res['ScheduleInt']; ?></td>
                                                <td><?php echo $res['ScheduleDate']; ?></td>
                                                <td><?php echo $res['ScheduleCnt']; ?></td>
                                                <td><?php echo $res['BalanceAmount']; ?></td>
                                                <td><?php echo $res['LastPaidDate']; ?></td>
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
    $('#loandue1').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
            leftColumns: 1,
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan overdue List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ') - Loan overdue List';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ') - Loan Overdue List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan Overdue List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Loan Overdue List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Overdue List - ' . $_POST['date1'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan Overdue List - ' . $_POST['date1'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Overdue List ' . $cdate . '  )</h5>';
}
?>',
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
                                'padding': '5pt',
                                'font-size': '10pt',
                                'margin': '1px'
                            });
                }

            }
        ]
    });


</script>





