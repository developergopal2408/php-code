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
                <small>Analysis Loan List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Analysis Loan List</li>
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
                                                $query = "SELECT ID,Name,Code from OfficeDetail";
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
                                            <button type="submit" name="search" data-toggle = "tooltip" title="Search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <a  href="analysisloan.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

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
                                    echo "<h5 class='text-center text-bold'> ".$_POST['date1'] . " - " . $_POST['date2'] . " -  All Branch</h5>";
                                } else {
                                    echo "<h5 class='text-center text-bold'> ".$_POST['date1'] . " - " . $_POST['date2'] . " - " . $bname. "</h5>";
                                }
                            } 
                            ?>

                            <table id="lanalysis" class="stripe row-border order-column" cellspacing="0" width="50%"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>Office Code</th>
                                        <th>Office Name</th>
                                        <th>QTY</th>
                                        <th>Analysis Approved Loan</th>

                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $al = $tot = 0;
                                    $ID = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if (isset($_POST['search'])) {
                                        if ($ID == "all") {
                                            $qry = "select o.code,o.name,
                                                (select count(memberid)QTY from analysisloan where AnalyzedDate>='$date1' and AnalyzedDate<='$date2' and officeid=o.id )QTY,
                                                (select sum(ApprovedLoan) from analysisloan where AnalyzedDate>='$date1' and AnalyzedDate<='$date2' and officeid=o.id  )AnalysisApprovedLoan
                                                from officedetail o
                                                where ID not in(1,51,52,53,54,55,57,61,80,81,82)
                                                order by o.code";
                                        } else {
                                            $qry = "select o.code,o.name,
                                                (select count(memberid)QTY from analysisloan where AnalyzedDate>='$date1' and AnalyzedDate<='$date2' and officeid=o.id )QTY,
                                                (select sum(ApprovedLoan) from analysisloan where AnalyzedDate>='$date1' and AnalyzedDate<='$date2' and officeid=o.id  )AnalysisApprovedLoan
                                                from officedetail o
                                                where ID not in(1,51,52,53,54,55,57,61,80,81,82) and ID = '$ID'
                                                order by o.code";
                                        }
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        $tot += $res['QTY'];
                                        $al += $res['AnalysisApprovedLoan'];
                                        ?>
                                        <tr>
                                            <td><?php echo $res['code']; ?></td>
                                            <td><?php echo $res['name']; ?></td>
                                            <td><?php echo $res['QTY']; ?></td>
                                            <td><?php
                                                if ($res['AnalysisApprovedLoan'] == "") {
                                                    echo "0.00";
                                                } else {
                                                    echo number_format($res['AnalysisApprovedLoan'], 2);
                                                }
                                                ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="bg-red text-sm">
                                    <tr>
                                        <td colspan=2>Total</td>
                                        <td><?php echo $tot; ?></td>
                                        <td class="text-right"><?php echo number_format($al, 2); ?></td>
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
    $('#lanalysis').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan Analysis List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo $bname . ' ( '. $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Analysis List';
    } else {
        echo 'All Branch ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Analysis List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan Analysis List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Loan Analysis List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan Analysis List -' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">All Branch <br/> ( Loan Analysis List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Analysis List ' . $cdate . '  )</h5>';
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








