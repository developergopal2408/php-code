<?php
include_once 'top.php';
include_once 'header.php';
?>

<style>
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
                <small>Loan Ledger</small>
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
                                <p class="text-bold text-center">Loan Ledger</p>

                                <div class="col-sm-12">
                                    <div class="text-sm">
                                        <b class="pull-left">From Date : <?php echo $sdate; ?></b> 
                                        <b class="pull-right">To Date : <?php echo $cdate; ?></b>
                                    </div>
                                </div>
                                <hr>

                                <?php
                                $lid = $_GET['lid'];
                                $mid = $_GET['mid'];
                                $memberquery = "SELECT * FROM member where officeid = '$BranchID' AND STATUS = 'ACTIVE' AND MemberID = '$mid' ";
                                $memberresult = odbc_exec($connection, $memberquery);
                                $row = odbc_fetch_array($memberresult);
                                $cid = $row['CenterID'];
                                //echo $cid;

                                $grquery = "SELECT * FROM MemberGroup where MemberGroupID = '" . $row['GroupID'] . "'";
                                $grresult = odbc_exec($connection, $grquery);
                                $gr = odbc_fetch_array($grresult);

                                $disquery = "SELECT * FROM District where DistrictID = '" . $row['DistrictID'] . "'";
                                $disresult = odbc_exec($connection, $disquery);
                                $dis = odbc_fetch_array($disresult);

                                $vdcquery = "SELECT * FROM VDC where DistrictID = '" . $row['DistrictID'] . "' AND VdcID = '" . $row['VdcID'] . "'";
                                $vdcresult = odbc_exec($connection, $vdcquery);
                                $vdc = odbc_fetch_array($vdcresult);


                                $centerquery = "SELECT * FROM CenterMain where officeid = '$BranchID' AND CenterID = '" . $row['CenterID'] . "'";
                                $centerresult = odbc_exec($connection, $centerquery);
                                $cen = odbc_fetch_array($centerresult);

                                $loanquery = "SELECT * FROM loanmain where officeid = '$BranchID' AND CenterID = '" . $row['CenterID'] . "' and MemberID = '$mid'  and LoanMainID = '$lid'";
                                $loanresult = odbc_exec($connection, $loanquery);
                                $loan = odbc_fetch_array($loanresult);

                                $intcrquery = "SELECT * FROM intcroptionloan where  IntCrOptionID = '" . $loan['IntCrOptionID'] . "'";
                                $intcrresult = odbc_exec($connection, $intcrquery);
                                $intcr = odbc_fetch_array($intcrresult);

                                $ldquery = "SELECT sum(loandr - loancr)bal FROM loandetail where officeid = '$BranchID'  and MemberID = '$mid'  and LoanMainID = '$lid'";
                                $ldresult = odbc_exec($connection, $ldquery);
                                $loandetail = odbc_fetch_array($ldresult);

                                $staffquery = "SELECT * FROM StaffMain where  StaffID = '" . $loan['UserID'] . "'";
                                $staffresult = odbc_exec($connection, $staffquery);
                                $staffrow = odbc_fetch_array($staffresult);

                                $ltquery = "SELECT * FROM loantype where LoanTypeID = '" . $loan['LoanTypeID'] . "'";
                                $ltresult = odbc_exec($connection, $ltquery);
                                $tloan = odbc_fetch_array($ltresult);

                                $lhquery = "SELECT * FROM loanheading where LoanHeadingID = '" . $loan['LoanHeadingID'] . "'";
                                $lhresult = odbc_exec($connection, $lhquery);
                                $hloan = odbc_fetch_array($lhresult);
                                ?>

                                <table class="table no-border table-condensed " style="font-size:11px;">

                                    <tbody class="text-bold">
                                        <tr>
                                            <td>Center : <?php echo $cen['CenterName']; ?></td>
                                            <td>Member Code :  <?php echo $row['MemberCode']; ?></td>
                                            <td>M.Name: <?php echo $row['FirstName'] . " " . $row['LastName']; ?></td>
                                            <td>Address : <?php echo $row['Tole'] . " - " . $row['WardNo'] . ", " . $vdc['VdcName'] . " , " . $dis['DistrictName']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Loan Type: <?php echo $tloan['LoanType']; ?></td>
                                            <td>Loan No : <?php echo $loan['LoanNo']; ?></td>
                                            <td>Loan Purpose : <?php echo $hloan['LoanHeading']; ?></td>
                                            <td>Period : <?php echo $loan['LoanPeriod']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Disburse Date : <?php echo $loan['IssueDate']; ?></td>
                                            <td>Maturity Date : <?php echo $loan['MaturityDate']; ?></td>
                                            <td>Disburse Amt : <?php echo number_format($loan['LoanAmount'], 2); ?></td>
                                            <td>Int Rate : <?php echo $loan['IntRate']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Outstanding : <?php echo $loandetail['bal']; ?></td>
                                            <td>Inst.Amt : <?php echo $loan['InstAmount']; ?></td>
                                            <td>Account Status : <?php echo $loan['IsCleared']; ?> </td>
                                            <td>Closed Date: </td>
                                        </tr>
                                        <tr>
                                            <td>Staff Name: <?php echo $staffrow['FirstName'] . "  " . $staffrow['LastName']; ?></td>
                                            <td>PayType: <?php echo $intcr['IntCrOption'];?></td>

                                        </tr>
                                    </tbody>

                                </table>


                                <hr>
                                <table  class="table table-bordered table-condensed text-center" style="font-size:11px;">
                                    <thead class="bg-red">
                                        <tr>
                                            <th>Date</th>
                                            <th>Particulars</th>
                                            <th>Debit Amount</th>
                                            <th>Credit Amount</th>
                                            <th>Balance</th>
                                            <th>Interest Received</th>

                                        </tr>
                                    </thead>
                                    <tbody class="text-bold">
                                        <?php
                                        $lcr = 0.0;
                                        $ldr = 0.0;
                                        $bal = 0.0;
                                        $totalint = 0.0;
                                        $qry = odbc_exec($connection, "select SaveDate,Particulars,LoanCr,LoanDr,IntCr "
                                                . "from Loandetail where OfficeID = '$BranchID' and MemberID = '$mid' "
                                                . "and LoanMainID = '$lid'");
                                        while ($row = odbc_fetch_array($qry)) {
                                            $lcr +=$row['LoanCr'];
                                            $ldr +=$row['LoanDr'];
                                            $bal = $bal + ($row['LoanDr'] - $row['LoanCr']);
                                            $totalint +=$row['IntCr'];
                                            ?>
                                            <tr>
                                                <td><?php echo $row['SaveDate']; ?></td>
                                                <td><?php echo $row['Particulars']; ?></td>
                                                <td><?php echo $row['LoanDr']; ?></td>
                                                <td><?php echo $row['LoanCr']; ?></td>
                                                <td><?php echo $bal; ?></td>
                                                <td><?php echo $row['IntCr']; ?></td>

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


    $('#mid').change(function () {
        var mid = $(this).val();
        $.ajax({
            type: "POST",
            url: "getloanlist.php",
            data: "mid=" + mid, // serializes the form's elements.
            success: function (data)
            {
                //alert(data); // show response from the php script.
                $("#ltype").html(data);
            }
        });
    });



</script>
