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
                <small>Loan Ledger List</small>
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

                                            <div class="col-sm-4" >
                                                <select name="cid" id="cid" class="form-control select2">
                                                    <option value="">Select Center</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM centermain where officeid = '" . $_SESSION['BranchID'] . "'  ";
                                                    $result = odbc_exec($connection, $sql1);
                                                    while ($rows = odbc_fetch_array($result)) {
                                                        ?>
                                                        <option  value="<?php echo $rows['CenterID']; ?>" <?php
                                                        if ($rows['CenterID'] == $_POST['cid']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $rows['CenterCode'] . " - " . $rows['CenterName']; ?></option>
                                                                 <?php
                                                             }
                                                             ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-4" id="mlist" ></div>

                                            <div class="col-sm-1">
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.search form -->
                                    <a href="loanledger.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $qry = odbc_exec($connection, "select * from centermain where officeid = '$BranchID' and  centerid = '" . $_POST['cid'] . "'");
                                $crow = odbc_fetch_array($qry);
                                $cname = $crow['CenterName'];
                                $qry1 = odbc_exec($connection, "select * from member where officeid = '$BranchID' and centerid = '" . $_POST['cid'] . "' and memberid = '" . $_POST['mid'] . "'");
                                $mrow = odbc_fetch_array($qry1);
                                $mname = $mrow['MemberCode'] . " - " . $mrow['FirstName'] . " " . $mrow['LastName'];

                                echo "<h5 class='text-center text-bold'>" . $branchName . " - " . $mname . "</h5>";
                            }
                            ?>
                            <div class="col-md-12 " id="printableArea">
                                <table id="loanledger" class="table table-bordered table-condensed" >
                                    <thead class="bg-red text-sm" style="font-size:9.5px;">
                                        <tr>
                                            <th>LoanMainID</th>
                                            <th>LoanType</th>
                                            <th>LoanHeading</th>
                                            <th>LoanNo</th>
                                            <th>LoanPeriod</th>
                                            <th>IssueDate</th>
                                            <th>LoanAmount</th>
                                            <th>IsCleared</th>
                                            <th>Loan Ledger</th>
                                            <th>Loan Repayment</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm" style="font-size:9.5px;">
                                        <?php
                                        $cid = $_POST['cid'];
                                        $mid = $_POST['mid'];
                                        $qry = odbc_exec($connection, "select l.MemberID,l.LoanMainID,t.LoanType,h.LoanHeading,
                                                                        l.LoanNo,l.LoanPeriod,l.IssueDate,l.LoanAmount,l.IsCleared 
                                                                        from LoanMain l,LoanType t,LoanHeading h
                                                                        where l.LoanTypeID = t.LoanTypeID 
                                                                        and h.LoanHeadingID = l.LoanHeadingID 
                                                                        and l.OfficeID = '$BranchID' and l.MemberID = '$mid' and l.CenterID = '$cid'
                                                                        ");
                                        while ($rozy = odbc_fetch_array($qry)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $rozy['LoanMainID']; ?></td>
                                                <td><?php echo $rozy['LoanType']; ?></td>
                                                <td><?php echo $rozy['LoanHeading']; ?></td>
                                                <td><?php echo $rozy['LoanNo']; ?></td>
                                                <td><?php echo $rozy['LoanPeriod']; ?></td>
                                                <td><?php echo $rozy['IssueDate']; ?></td>
                                                <td><?php echo $rozy['LoanAmount']; ?></td>
                                                <td><?php echo $rozy['IsCleared']; ?></td>
                                                <td class="text-center">
                                                    <a href="loan_ledger.php?lid=<?php echo $rozy['LoanMainID']; ?>&mid=<?php echo $rozy['MemberID']; ?>" target="_new" class="btn btn-xs bg-blue-active">Loan Ledger</a>

                                                </td>
                                                <td>                                                   
                                                    <a href="loan_repayment_schedule.php?lid=<?php echo $rozy['LoanMainID']; ?>&mid=<?php echo $rozy['MemberID']; ?>" target="_new" class="btn btn-xs bg-green-active">Loan Repayment</a>
                                                </td>
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
        $('#loanledger').DataTable({
            'order': [5, 'desc'],
            'scrollY': "300px",
            'scrollCollapse': true,
            'paging': false,
        });


        $('#cid').change(function () {
            var cid = $(this).val();
            $.ajax({
                type: "POST",
                url: "getmemberlist.php",
                data: "cid=" + cid, // serializes the form's elements.
                success: function (data)
                {
                    //alert(data); // show response from the php script.
                    $("#mlist").html(data);
                }
            });
        });

    });

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

</script>
