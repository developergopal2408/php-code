<?php
include_once 'top.php';
include_once 'header.php';
?>
<style>
    table.table-bordered > tbody > tr > td{
        border:1px solid blue;
    }

    table.table-bordered > tfoot > tr > td{
        border:1px solid blue;
    }
    
    @media print
    {
        body * { visibility: hidden; }
        #printcontent * { visibility: visible; }
        #printcontent { position: absolute; top: 40px; left: 30px; }
    }

</style>
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
                <small>Loan Repayment Schedule</small>
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
                        <div class="box-body">
                            <div class="col-md-12 text-sm" id="printableArea">
                                <h4 class="text-center text-bold">
                                    JEEVAN BIKAS SAMAJ<br/>
                                </h4>
                                <p class="text-bold text-center"><?php echo $branchName; ?></p>
                                <p class="text-bold text-center">Loan Repaymnet Schedule</p>
                                <hr class="no-print">
                                <?php
                                $lid = $_GET['lid'];
                                $mid = $_GET['mid'];
                                $memberquery = "SELECT * FROM member where officeid = '$BranchID' AND STATUS = 'ACTIVE' AND MemberID = '$mid' ";
                                $memberresult = odbc_exec($connection, $memberquery);
                                $row = odbc_fetch_array($memberresult);
                                $cid = $row['CenterID'];

                                $grquery = "SELECT * FROM MemberGroup where MemberGroupID = '" . $row['GroupID'] . "'";
                                $grresult = odbc_exec($connection, $grquery);
                                $gr = odbc_fetch_array($grresult);

                                $disquery = "SELECT * FROM District where DistrictID = '" . $row['DistrictID'] . "'";
                                $disresult = odbc_exec($connection, $disquery);
                                $dis = odbc_fetch_array($disresult);

                                $vdcquery = "SELECT * FROM VDC where DistrictID = '" . $row['DistrictID'] . "' AND VdcID = '" . $row['VdcID'] . "'";
                                $vdcresult = odbc_exec($connection, $vdcquery);
                                $vdc = odbc_fetch_array($vdcresult);

                                $loanquery = "SELECT * FROM loanmain where officeid = '$BranchID' AND CenterID = '" . $row['CenterID'] . "' and MemberID = '$mid'  and LoanMainID = '$lid'";
                                $loanresult = odbc_exec($connection, $loanquery);
                                $loan = odbc_fetch_array($loanresult);

                                $intcrquery = "SELECT * FROM intcroptionloan where  IntCrOptionID = '" . $loan['IntCrOptionID'] . "'";
                                $intcrresult = odbc_exec($connection, $intcrquery);
                                $intcr = odbc_fetch_array($intcrresult);

                                $ltquery = "SELECT * FROM loantype where LoanTypeID = '" . $loan['LoanTypeID'] . "'";
                                $ltresult = odbc_exec($connection, $ltquery);
                                $tloan = odbc_fetch_array($ltresult);

                                $lhquery = "SELECT * FROM loanheading where LoanHeadingID = '" . $loan['LoanHeadingID'] . "'";
                                $lhresult = odbc_exec($connection, $lhquery);
                                $hloan = odbc_fetch_array($lhresult);
                                ?>
                                <table class="table table-bordered table-condensed " style="font-size:10px;">
                                    <thead class="bg-green-active" >
                                        <tr>
                                            <th>M.Code</th>
                                            <th>M.Name</th>
                                            <th>Loan No </th>
                                            <th>Loan Type </th>
                                            <th>Loan Purpose</th>
                                            <th>Payment Type </th>
                                            <th>Installment No </th>
                                            <th>Disburse Amount </th>
                                            <th>Disburse Date </th>
                                            <th>Maturity Date </th>
                                            <th>Loan Period </th>
                                            <th>Annual Int. Rate </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $row['MemberCode']; ?></td>
                                            <td><?php echo $row['FirstName'] . " " . $row['LastName']; ?></td>
                                            <td><?php echo $loan['LoanNo']; ?></td>
                                            <td><?php echo $tloan['LoanType']; ?></td>
                                            <td> <?php echo $hloan['LoanHeading']; ?></td>
                                            <td><?php echo $intcr['IntCrOption']; ?></td>
                                            <td><?php echo $loan['InstallementNo']; ?></td>
                                            <td><?php echo number_format($loan['LoanAmount'], 2); ?></td>
                                            <td><?php echo $loan['IssueDate']; ?></td>
                                            <td><?php echo $loan['MaturityDate']; ?></td>
                                            <td><?php echo $loan['LoanPeriod']; ?></td>
                                            <td><?php echo $loan['IntRate']; ?></td>
                                        </tr>
                                    </tbody>

                                </table>


                                <table  class="table table-bordered table-condensed text-center" style="font-size:10px;">
                                    <thead class="bg-red">
                                        <tr>
                                            <th>SNo.</th>
                                            <th>Payment Date(BS)</th>
                                            <th>Principal</th>
                                            <th>Interest</th>
                                            <th>Installment Amount</th>
                                            <th>Outstanding Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        $totalint = $totalpri = $totalinst = $endbal = 0.0;
                                        $qry = odbc_exec($connection, "select * 
                                                                        from memberloanschedule 
                                                                        where officeid = '$BranchID' and loanmainid = '$lid' 
                                                                        order by paymentdate");
                                        while ($row = odbc_fetch_array($qry)) {
                                            $totalpri += $row['PriAmt'];
                                            $totalint += $row['IntAmt'];
                                            $totalinst += $row['InstAmt'];
                                            //$endbal = $row['EndBalance'];
                                            ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><?php echo $row['PaymentDate']; ?></td>
                                                <td><?php echo $row['PriAmt']; ?></td>
                                                <td><?php echo $row['IntAmt']; ?></td>
                                                <td><?php echo $row['InstAmt']; ?></td>
                                                <td><?php echo $row['EndBalance']; ?></td>
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
    $(document).ready(function () {
        $('#loanledger').DataTable();
    });


    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>
