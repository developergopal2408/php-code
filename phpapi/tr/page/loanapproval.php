<?php
include_once 'top.php'; //Include Sidebar_header.php-->
include_once 'header.php'; //Include Sidebar.php-->
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
                <small>Loan Approval</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Approval</li>
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
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date1'])) {
                                                echo $_POST['date1'];
                                            } else {
                                                echo $sdate;
                                            }
                                            ?>">
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date2'])) {
                                                echo $_POST['date2'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
                                        <?php
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" >
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $query = "SELECT ID,Name,Code from OfficeDetail";
                                                    $sub = sqlsrv_query($connection, $query);
                                                    while ($p = sqlsrv_fetch_array($sub)) {
                                                        ?>
                                                        <option value="<?php echo $p['ID']; ?>" <?php
                                                        if ($p['ID'] == $_POST['id']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
                                                                <?php
                                                            }
                                                            ?>
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
                                <div class="box-tools pull-right" >
                                    <a href="loanapproval.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            $bname = "";
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = sqlsrv_query($connection, $query);
                                $p = sqlsrv_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['id']) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                }
                            }
                            ?>

                            <table id="loanapproval" class="stripe row-border order-column" cellspacing="0" width="100%"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>MCode</th>
                                        <th>MNAME</th>
                                        <th>Name</th>
                                        <th>SpouseFather</th>
                                        <th>Loantype</th>
                                        <th>Loanheading</th>
                                        <th>LoanNo</th>
                                        <th>DemandDate</th>
                                        <th>DemandLoan</th>
                                        <th>AnalysisDate</th>
                                        <th>NetCash</th>
                                        <th>NetWorth</th>
                                        <th>Submited</th>
                                        <th>Signature</th>
                                        <th>ApploanAmt</th>
                                        <th>Signature</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $demand = $net = $nw = 0;
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    $id = $_POST['id'];
                                    if ($_SESSION['BranchID'] == 1) {
                                        $idx = "and d.officeid='$id'";
                                        $changedate = "and d.savedate between '$date1' and '$date2'";
                                    } else {
                                        $idx = "and d.officeid='" . $_SESSION['BranchID'] . "'";
                                        $changedate = "and d.savedate between '$date1' and '$date2'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select (select Name from officedetail where id = a.officeid)Name,m.membercode,m.firstname+' '+ m. Lastname as MemberName,m.SpouseFather,t.loantype,h.loanheading,a.LoanNo,(d.savedate)DemandDate,
                                            a.DemandLoan,max(analyzedDate)AnalysisDate,a.NetCash,a.NetWorth,
                                            (select code from staffmain where a.userid=staffid and branchid=a.officeid)Submited,''Signature,''ApploanAmt,''Signature
                                            from member m, analysisloan a,loantype t,loanheading h,DemandLoan d
                                            where m.memberid=a.memberid and m.officeid=a.officeid and m.officeid=d.officeid and t.loantypeid=a.loantypeid and h.loanheadingid=a.loanheadingid and d.demandloanid=a.demandloanid
                                            $changedate and t.loantypeid<>2 and a.analysisloanid not in(select analysisloanid from loanmain where officeid=a.officeid)
                                            $idx
                                            group by m.membercode,m.firstname,m. Lastname ,m.SpouseFather,t.loantype,h.loanheading,a.LoanNo,a.DemandLoan,a.analyzedDate,d.savedate
                                            ,d.memberid,d.officeid,a.userid,a.officeid,a.NetCash,a.NetWorth
                                            order by m.membercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        $demand += $res['DemandLoan'];
                                        $net += $res['NetCash'];
                                        $nw += $res['NetWorth'];
                                        ?>
                                        <tr>
                                            <td><?php echo $res['membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['Name']; ?></td>
                                            <td><?php echo $res['SpouseFather']; ?></td>
                                            <td><?php echo $res['loantype']; ?></td>
                                            <td><?php echo $res['loanheading']; ?></td>
                                            <td><?php echo $res['LoanNo']; ?></td>
                                            <td><?php echo $res['DemandDate']; ?></td>
                                            <td><?php echo number_format($res['DemandLoan'], 2); ?></td>
                                            <td><?php echo $res['AnalysisDate']; ?></td>
                                            <td><?php echo number_format($res['NetCash'], 2); ?></td>
                                            <td><?php echo number_format($res['NetWorth'], 2); ?></td>
                                            <td><?php echo $res['Submited']; ?></td>
                                            <td><?php echo $res['Signature']; ?></td>
                                            <td><?php echo $res['ApploanAmt']; ?></td>
                                            <td><?php echo $res['Signature']; ?></td>
                                        </tr>																				
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="text-sm bg-red">
                                    <tr>
                                        <td colspan=8>Total</td>
                                        <td colspan=2><?php echo number_format($demand, 2); ?></td>
                                        <td><?php echo number_format($net, 2); ?></td>
                                        <td><?php echo number_format($nw, 2); ?></td>
                                        <td colspan=4></td>
                                    </tr>
                                </tfoot>
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
<script>
    $('#loanapproval').removeAttr('width').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        columnDefs: [
            { width: 150, targets: [1,2,3,4]}
        ],
        fixedColumns: true,
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan Approval',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Approval';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Approval';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan Approval';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Loan Approval',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Approval - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan Approval - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Approval ' . $cdate . '  )</h5>';
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


