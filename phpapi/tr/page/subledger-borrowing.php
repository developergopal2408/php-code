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
                <small>Sub-Ledger</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Sub-Ledger </li>
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
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <a href="subledger-borrowing.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>
                        <div class="box-body">
                            <table id="borrow" class="stripe row-border order-column" cellspacing="0" width="100%">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>VNO</th>
                                        <th>Narration</th>
                                        <th>Dr</th>
                                        <th>Cr</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    $bal = 0.0;
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    $query = "SELECT ID,Name,LF FROM acctree  WHERE parentid = '271' AND NAME like '%$branchName%'";
                                    $sub = sqlsrv_query($connection, $query);
                                    $p = sqlsrv_fetch_array($sub);
                                    $selectedAcc = $p['ID'];
                                    if(isset($_POST['search'])){
                                    $qry = "select '$date1' as Date,'0B/F' as Vno,'Brought Forward' as Description, DrAmount=
                                                CASE when sum(l.dramount-l.cramount)>0 then
                                                sum(l.dramount-l.cramount) 
                                               else 0 end,CrAmount=CASE when 
                                               sum(l.dramount-l.cramount)<0 then
                                               sum(l.dramount-l.cramount) 
                                               else 0 end
                                               from acctree a,ledger l
                                               where a.id=l.accountheadid and l.ldate>='2074/04/01' and l.ldate<'$date1' and a.parentid=271
                                               and a.id='$selectedAcc'
                                               union all
                                               select l.ldate as Date,CAST(v.Vno AS varchar(5)) as Vno,v.Narration as Description,l.dramount as DrAmount,l.cramount as CrAmount
                                               from acctree a,ledger l,vouchermaster v
                                               where a.id=l.accountheadid and l.ldate>='$date1' and l.ldate <= '$date2' and a.parentid=271
                                               and v.id=l.vno and a.id=' $selectedAcc'
                                               order by Vno,Date";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {

                                        $bal = $bal + ($res['DrAmount'] - $res['CrAmount']);
                                        ?>
                                        <tr>
                                            <td><?php echo ++$count; ?></td>
                                            <td><?php echo $res['Date']; ?></td>
                                            <td><?php echo $res['Vno']; ?></td>
                                            <td><?php echo $res['Description']; ?></td>
                                            <td><?php echo number_format($res['DrAmount'], 2); ?></td>
                                            <td><?php echo number_format($res['CrAmount'], 2); ?></td>
                                            <td><?php echo number_format($bal, 2); ?></td>
                                            <td>
                                                <?php
                                                if ($count != 1) {
                                                    ?>
                                                    <a href="vdetail.php?Vno=<?php echo $res['Vno']; ?>&Date=<?php echo $res['Date']; ?>" target="_blank"  class="btn btn-sm bg-blue">View Details</a>

                                                    <?php
                                                }
                                                ?>
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
    $('#borrow').DataTable({
        //scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Subledger-Borrowing Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Subledger-Borrowing Detail';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Subledger-Borrowing Detail';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Subledger-Borrowing Detail';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Subledger-Borrowing Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Subledger-Borrowing Detail - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Subledger-Borrowing Detail - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Subledger-Borrowing Detail ' . $cdate . '  )</h5>';
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