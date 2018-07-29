<?php
include_once 'top.php';
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
                <small>Loan Overdue Report</small>
            </h1>
            <ol class="breadcrumb">
                <button type="button" class="btn btn-sm bg-blue" onClick="printDiv('printableArea')"  ><i class="glyphicon glyphicon-print"></i></button>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <!-- search form -->
                                    <form  action="" method="post" class="form-horizontal" >
                                        <div class=" form-group-sm">

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
                                            <?php
                                            if ($_SESSION['BranchID'] == 1) {
                                                ?>
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
                                                <?php
                                            }
                                            ?>
                                            <div class="col-sm-1">
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.search form -->
                                    <a href="loanoverdue.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="col-md-12 text-sm" id="printableArea">
                                <h4 class="text-center text-bold">
                                    JEEVAN BIKAS SAMAJ<br/>
                                </h4>
                                <p class="text-bold text-center"><?php echo $branchName; ?></p>
                                <p class="text-bold text-center">Loan Overdue Report</p>

                                <div class="col-sm-12">
                                    <div class="text-sm">
                                        <b class="pull-right">As On : <?php echo $cdate; ?></b>
                                    </div>
                                </div>
                                <table id="lo"  class="table table-bordered table-condensed text-center text-sm" style="font-size:10.5px;">
                                    <thead class="bg-red">
                                        <tr>
                                            <th>M.Code</th>
                                            <th>M.Name</th>
                                            <th>Mobile No</th>
                                            <th>L.Type</th>
                                            <th>L.Heading</th>
                                            <th>Dis.Date</th>
                                            <th>Maturity Date</th>
                                            <th>Disburse.Amt</th>
                                            <th>Out.Amt</th>
                                            <th>Paid Amount</th>
                                            <th>Unpaid Principal</th>
                                            <th>Unpaid Interest</th>
                                            <th>Total Unpaid</th>
                                            <th>Arrears (Days)</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $date1 = $_POST['date2'];
                                        $id = $_POST['oid'];
                                        if ($BranchID == 1) {
                                            $idx = "DECLARE @p_officeid int = '$id'";
                                        } else {
                                            $idx = "DECLARE @p_officeid int = '$BranchID'";
                                        }

                                        list($yr1, $mn1, $dy1) = explode("/", $date1);
                                        $npdate = $cal->nep_to_eng($yr1, $mn1, $dy1);
                                        $yr = $npdate['year'];
                                        $mn = $npdate['month'];
                                        $dy = $npdate['date'];
                                        $date2 = $yr . "-" . $mn . "-" . $dy;
                                        //echo $date2;
                                        $qry = "DECLARE @p_date date = '$date2'
                                        $idx
                                        --DECLARE @p_loanmainid int = 833
                                        --SELECT * FROM (
                                        SELECT --TOP 10 
                                        lm.OfficeID, lm.LoanMainID, lm.MemberID,mm.MemberCode,mm.MobileNo
                                        , mm.FirstName+' '+mm.LastName AS MemberName
                                        --, mm.CenterID, mm.GroupID
                                        , lm.LoanHeadingID, lm.LoanNo
                                        , lm.IssueDate AS LoanDate, lm.IssueDateAD, lm.MaturityDate, lm.LoanAmount
                                        , dbo.GetArrearsDaysAD(lm.OfficeID, lm.LoanMainID, @p_date) AS ArrearsDays
                                        , ld.PriPaid, ld.IntPaid
                                        , ls.SchedulePri, ls.ScheduleInt, ls.ScheduleDate
                                          FROM LoanMain AS lm, Member mm,
                                                (SELECT ls.OfficeID, ls.LoanMainID, MIN(PaymentDate) AS ScheduleDate, 
                                                                ISNULL(SUM(ls.PriAmt),0) AS SchedulePri, ISNULL(SUM(ls.IntAmt),0) AS ScheduleInt
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
                                                   AND ld.SaveDateAD <= @p_date
                                                 GROUP BY ld.OfficeID, ld.LoanMainID) AS ld
                                         WHERE lm.OfficeID = @p_officeid --and MemberID=1 
                                         --AND lm.LoanMainID = @p_loanmainid
                                         AND lm.IssueDateAD <= @p_date
                                         AND lm.OfficeID = ls.OfficeID
                                         AND lm.LoanMainID = ls.LoanMainID
                                         AND lm.OfficeID = ld.OfficeID
                                         AND lm.LoanMainID = ld.LoanMainID
                                         AND lm.OfficeID = mm.OfficeID
                                         AND lm.MemberID = mm.MemberID
                                         AND ld.PriPaid < ls.SchedulePri
                                         --) AS x
                                         --WHERE x.PriPaid<x.SchedulePri";


                                        $run = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($run)) {
                                            $member = odbc_exec($connection, "select * from member where memberid = '" . $res['MemberID'] . "' AND officeid = '$BranchID'");
                                            $mob = odbc_fetch_array($member);

                                            $loanquery = "SELECT * FROM loanmain where  LoanMainID = '" . $res['LoanMainID'] . "'";
                                            $loanresult = odbc_exec($connection, $loanquery);
                                            $loan = odbc_fetch_array($loanresult);

                                            $ltquery = "SELECT * FROM loantype where LoanTypeID = '" . $loan['LoanTypeID'] . "'";
                                            $ltresult = odbc_exec($connection, $ltquery);
                                            $tloan = odbc_fetch_array($ltresult);

                                            $lhquery = "SELECT * FROM loanheading where LoanHeadingID = '" . $res['LoanHeadingID'] . "'";
                                            $lhresult = odbc_exec($connection, $lhquery);
                                            $hloan = odbc_fetch_array($lhresult);
                                            ?>
                                            <tr>
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['MobileNo']; ?></td>
                                                <td><?php echo $tloan['LoanType']; ?></td>
                                                <td><?php echo $hloan['LoanHeading']; ?></td>
                                                <td><?php echo $res['LoanDate']; ?></td>
                                                <td><?php echo $res['MaturityDate']; ?></td>
                                                <td><?php echo $res['LoanAmount']; ?></td>
                                                <td><?php echo $res['LoanAmount'] - $res['PriPaid']; ?></td>
                                                <td><?php echo $res['PriPaid']; ?></td>
                                                <td><?php echo $res['SchedulePri'] - $res['PriPaid']; ?></td>
                                                <td><?php echo (abs($res['ScheduleInt'] - $res['IntPaid'])); ?></td>
                                                <td><?php echo ($res['SchedulePri'] - $res['PriPaid']) + ($res['ScheduleInt'] - $res['IntPaid']); ?></td>
                                                <td><?php echo $res['ArrearsDays']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div> 
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

<script>
    $('#lo').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        columnDefs: [
            {
                width: '10%', 
                targets: 0}
        ],
        fixedColumns: {
            leftColumns: 2,
        },
        buttons: [
            {
                extend: 'excel',
                filename: 'Overdue Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Overdue Detail ";
} else {
    echo $branchName . "- Overdue Detail ";
};
?>',
            },
            {
                extend: 'pdf',
                filename: 'Overdue Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Overdue Detail ";
} else {
    echo $branchName . "- Overdue Detail ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'Overdue Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "<br/> Overdue Detail ";
} else {
    echo $branchName . "<br/> Overdue Detail ";
};
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


    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>
