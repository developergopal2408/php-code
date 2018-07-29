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
                <small>Loan Disbursed</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Disbursed</li>
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
                                                <select name="id" id="id" class="form-control select2" required>
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
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >                                    
                                    <a  href="loandisbursed.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $bname = "";
                            if (isset($_POST['search'])) {
                                $date1 = $_POST['date1'];
                                $date2 = $_POST['date2'];
                                $q = "SELECT ID,Name FROM OfficeDetail  WHERE ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $q);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_SESSION['BranchID'] == 1) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " - Loan Disbursed List (" . $date1 . " - " . $date2 . ")</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . " - Loan Disbursed List (" . $date1 . " - " . $date2 . ")</h5>";
                                }
                            }
                            ?>
                            <table id="ld" class="stripe row-border order-column" cellspacing="0" width="100%">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>MCode</th>
                                        <th>MName</th>
                                        <th>SpouseFather</th>
                                        <th>FatherInLaw</th>
                                        <th>Date</th>
                                        <th>Ltype</th>
                                        <th>LHeading</th>
                                        <th>InttRate</th>
                                        <th>TnsType</th>
                                        <th>InsNo</th>
                                        <th>LAmt</th>
                                        <th>InstAmt</th>
                                    </tr>

                                </thead>
                                <?php
                                $ln = 0;
                                $date1 = $_POST['date1'];
                                $date2 = $_POST['date2'];
                                $id = $_POST['id'];
                                if ($_SESSION['BranchID'] == 1) {
                                    $idx = "and m.officeid='$id'";
                                } else {
                                    $idx = "and m.officeid='" . $_SESSION['BranchID'] . "'";
                                }
                                if (isset($_POST['search'])) {
                                    $query = "select m.Membercode,m.Firstname+' '+m.lastname as MemberName,m.Spousefather,m.Fatherinlaw,
                                        (l.issuedate)Date,t.loantype,l.intrate,(i.intcroption)Tnstype,h.loanheading,(l.installementno)InsNo,
                                        l.loanamount,l.instamount
					from member m, loanmain l, loantype t,intcroptionloan i,loanheading h
					where m.memberid=l.memberid and l.loantypeid=t.loantypeid and i.intcroptionid=l.intcroptionid and l.issuedate between '$date1' and '$date2'
					and m.officeid=l.officeid $idx and h.loanheadingid = l.loanheadingid
					order by m.membercode";
                                }
                                $results = odbc_exec($connection, $query);
                                ?>
                                <tbody class="text-sm">
                                    <?php
                                    while ($r = odbc_fetch_array($results)) {
                                        $ln = $ln + $r['loanamount'];
                                        ?>
                                        <tr>
                                            <td><?php echo $r['Membercode']; ?></td>
                                            <td><?php echo $r['MemberName']; ?></td>
                                            <td><?php echo $r['Spousefather']; ?></td>
                                            <td><?php echo $r['Fatherinlaw']; ?></td>
                                            <td><?php echo $r['Date']; ?></td>
                                            <td><?php echo $r['loantype']; ?></td>
                                            <td><?php echo $r['loanheading']; ?></td>
                                            <td><?php echo $r['intrate']; ?></td>
                                            <td><?php echo $r['Tnstype']; ?></td>
                                            <td><?php echo $r['InsNo']; ?></td>
                                            <td><?php echo number_format($r['loanamount'], 2); ?></td>
                                            <td><?php echo $r['instamount']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="bg-red text-sm">
                                    <tr>
                                        <td colspan="10" class="text-bold">Total</td>
                                        <td><?php echo number_format($ln, 2); ?></td>
                                        <td></td>
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
    $('#ld').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        fixedColumns: {
            leftColumns: 1,
        },
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan Disbursed List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Disbursed List';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Disbursed List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan Disbursed List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Loan Disbursed List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Disbursed List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan Disbursed List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Disbursed List ' . $cdate . '  )</h5>';
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
