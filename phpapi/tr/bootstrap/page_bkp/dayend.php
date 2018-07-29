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
                <small>Day End List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Day End List</li>
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
                                                ?>><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
                                                        <?php
                                                        }
                                                        ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle = "tooltip" title="Search"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <a  href="dayend.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">

                                <?php
                                if (isset($_POST['id'])) {
                                $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                echo $p['Name'];
                                } else {
                                echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="dayend" class="stripe row-border order-column" cellspacing="0" width="50%"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>Office Code</th>
                                        <th>Office Name</th>
                                        <th>Day End Date</th>
                                        <th>Cash Balance Till Dayend Date</th>
                                        <th>Total Bank</th>
                                        <th>Total Cash & Bank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cs = $bank = $tcd = 0;
                                    $ID = $_POST['id'];
                                    if (isset($_POST['search'])) {
                                    if ($ID == "all") {
                                    $qry = "select o.code,o.name,max(a.dayend)DayEndDate,
                                                (select sum(dramount-cramount)from ledger where o.id=officeid and ldate between'2074/04/01' and  max(a.dayend) and officeid=a.officeid
                                                and accountheadid=301)CashBalance,
                                                (select sum(dramount-cramount)from ledger where o.id=officeid and ldate between'2074/04/01' and  max(a.dayend) and officeid=a.officeid
                                                and accountheadid in(select accountid from bankname where officeid=ledger.officeid and categoryid in(306,307,308)))Bank
                                                from officedetail o, dayend a
                                                where o.id=a.officeid 
                                                group by o.code,o.name,o.id,a.officeid
                                                order by o.code";
                                    } else {
                                    $qry = "select o.code,o.name,max(a.dayend)DayEndDate,
                                                (select sum(dramount-cramount)from ledger where o.id=officeid and ldate between'2074/04/01' and  max(a.dayend) and officeid=a.officeid
                                                and accountheadid=301)CashBalance,
                                                (select sum(dramount-cramount)from ledger where o.id=officeid and ldate between'2074/04/01' and  max(a.dayend) and officeid=a.officeid
                                                and accountheadid in(select accountid from bankname where officeid=ledger.officeid and categoryid in(306,307,308)))Bank
                                                from officedetail o, dayend a
                                                where o.id=a.officeid and a.officeid = '$ID'
                                                group by o.code,o.name,o.id,a.officeid
                                                order by o.code";
                                    }
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                    $totalcb = $res['CashBalance'] + $res['Bank'];
                                    $cs += $res['CashBalance'];
                                    $bank += $res['Bank'];
                                    $tcd += $totalcb;
                                    ?>
                                    <tr>
                                        <td><?php echo $res['code']; ?></td>
                                        <td><?php echo $res['name']; ?></td>
                                        <td><?php echo $res['DayEndDate']; ?></td>
                                        <td class="text-right"><?php echo number_format($res['CashBalance'], 2); ?></td>
                                        <td class="text-right"><?php echo number_format($res['Bank'], 2); ?></td>
                                        <td class="text-right"><?php echo number_format($totalcb, 2); ?></td>
                                    </tr>																				
                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="text-bold bg-red">
                                    <tr>
                                        <td colspan=3>Total</td>
                                        <td class="text-right"><?php echo number_format($cs, 2); ?></td>
                                        <td class="text-right"><?php echo number_format($bank, 2); ?></td>
                                        <td class="text-right"><?php echo number_format($tcd, 2); ?></td>
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
    $('#dayend').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Dayend List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo $bname . ' ( ' . $cdate . ') - Dayend List';
    } else {
        echo 'All Branch ( ' . $cdate . ') - Dayend List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Dayend List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Dayend List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Dayend List -' . $cdate . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">All Branch <br/> ( Dayend List - ' . $cdtae . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Dayend List ' . $cdate . '  )</h5>';
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

