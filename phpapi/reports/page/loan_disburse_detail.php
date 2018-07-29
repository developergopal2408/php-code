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
                <small>Loan Disburse Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">LOan Disburse Detail</li>
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
                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Branch</option>
                                                <option value="all">All Branch</option>
                                                <?php
                                                $query = "SELECT ID,Name,Code from OfficeDetail where ID > 1";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" <?php
                                                    if ($p['ID'] == $_POST['id']) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                            </div>
                        </div>

                        <div class="box-body">
                            
                                 <?php
                            if (isset($_POST['id'])) {
                                $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['id'] == "all") {
                                    echo "<h5 class='text-center text-bold'> " . $_POST['date1'] . " - " . $_POST['date2'] . " -  All Branch</h5>";
                                } else {
                                    echo "<h5 class='text-center text-bold'> " . $_POST['date1'] . " - " . $_POST['date2'] . " - " . $bname . "</h5>";
                                }
                            }
                            ?>
                            
                            <table id="ddl" class="table table-bordered table-striped text-sm"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>M.Code</th>
                                        <th>M.Name</th>
                                        <th>LoanType</th>
                                        <th>D_Date</th>
                                        <th>Demand Loan</th>
                                        <th>AnalysedDate</th>
                                        <th>ApprovedDate</th>
                                        <th>ApprovedLoan</th>
                                        <th>Dis_Date</th>
                                        <th>Loan Amt</th>
                                    </tr>
                                </thead>

                                <?php
                                if (isset($_POST['search'])) {
                                    $dl = 0;
                                    $al = 0;
                                    $ll=0;
                                    $ID = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($ID == "all") {
                                        $qry = "select m.MemberCode,m.firstname+' '+m.lastname as MemberName,t.loantype,(d.savedate)D_date,d.DemandLoan,a.AnalyzedDate,a.ApprovedDate,a.ApprovedLoan,
                                                (l.issuedate)Dis_Date,l.Loanamount
                                                from demandloan d,analysisloan a,loanmain l,member m,loantype t
                                                where m.officeid=d.officeid and m.officeid=a.officeid and m.officeid=l.officeid and m.memberid=d.memberid and d.demandloanid=a.demandloanid and 
                                                a.analysisloanid=l.analysisloanid  and l.issuedate between '$date1' and '$date2' and t.loantypeid=l.loantypeid
                                                order by m.membercode";
                                    } else {
                                        $qry = "select m.MemberCode,m.firstname+' '+m.lastname as MemberName,t.loantype,(d.savedate)D_date,d.DemandLoan,a.AnalyzedDate,a.ApprovedDate,a.ApprovedLoan,
                                                (l.issuedate)Dis_Date,l.Loanamount
                                                from demandloan d,analysisloan a,loanmain l,member m,loantype t
                                                where m.officeid=d.officeid and m.officeid=a.officeid and m.officeid=l.officeid and m.memberid=d.memberid and d.demandloanid=a.demandloanid and 
                                                a.analysisloanid=l.analysisloanid and m.officeid='$ID' and l.issuedate between '$date1' and '$date2' and t.loantypeid=l.loantypeid
                                                order by m.membercode";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    ?>
                                    <tbody>
                                        <?php
                                        while ($res = odbc_fetch_array($result)) {
                                            $dl +=$res['DemandLoan'];
                                            $al +=$res['ApprovedLoan'];
                                            $ll +=$res['Loanamount'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $res['MemberCode']; ?></td>
                                                    <td><?php echo $res['MemberName']; ?></td>
                                                    <td><?php echo $res['loantype']; ?></td>
                                                    <td><?php echo $res['D_date']; ?></td>
                                                    <td><?php echo $res['DemandLoan']; ?></td>
                                                    <td><?php echo $res['AnalyzedDate']; ?></td>
                                                    <td><?php echo $res['ApprovedDate']; ?></td>
                                                    <td><?php echo $res['ApprovedLoan']; ?></td>
                                                    <td><?php echo $res['Dis_Date']; ?></td>
                                                    <td><?php echo $res['Loanamount']; ?></td>
                                                </tr>
                                                <?php
                                            
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot class="bg-red text-bold">
                                        <tr>
                                            <td colspan="4">Total</td>
                                            <td><?php echo $dl;?></td>
                                            <td colspan="2"></td>
                                            <td><?php echo $al;?></td>
                                            <td></td>
                                            <td><?php echo $ll;?></td>
                                        </tr>
                                    </tfoot>
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

<script>
    $('#ddl').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan DIsburse Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo $bname . ' ( '. $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan DIsburse Detail';
    } else {
        echo 'All Branch ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan DIsburse Detail';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan DIsburse Detail';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Demand Loan List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan DIsburse Detail -' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">All Branch <br/> ( Loan DIsburse Detail - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan DIsburse Detail ' . $cdate . '  )</h5>';
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

