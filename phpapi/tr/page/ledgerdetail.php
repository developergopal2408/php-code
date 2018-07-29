<?php
include_once 'top.php';
include_once 'header.php';
$Vno = $_REQUEST['Vno'];
$Date = $_REQUEST['Date'];
$oid = $_REQUEST['oid'];
//$id = $_REQUEST['id'];
?>
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
                <?php echo $branchName; ?>
                <small>Sub-Ledger</small> 
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Sub-Ledger</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <a href="subledger-borrowing.php" class="btn bg-red">Back To Main</a>
                        </div>
                        <div class="box-body">
                            <table id="voucher" class="stripe row-border order-column" cellspacing="0" width="100%">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Account Head</th>
                                        <th>Code No.</th>
                                        <th>Description</th>
                                        <th>Dr.Amount</th>
                                        <th>Cr.Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $totalcr = 0;
                                    $totaldr = 0;
									if($_SESSION['BranchID'] == 1){
										$idx = "and l.officeid = '$oid'";
										//$idw = "and a.id = '$id'";
									}else{
										$idx = "and l.officeid = '".$_SESSION['BranchID']."'";
										//$idw = "and a.id = '$id'";
									}
                                    $query = "select a.name as Name,l.ldate,v.Narration,v.vno,l.dramount,l.cramount,(a.LF) as Code
                                                from ledger l, vouchermaster v,acctree a
						where l.vno=v.id and l.ldate='$Date' and v.vno='" . intval($Vno) . "' and a.id = l.accountheadid and l.officeid=v.officeid  $idx
						order by l.ldate";
                                    $detail = sqlsrv_query($connection, $query);
                                    while ($row = sqlsrv_fetch_array($detail)) {
                                        $totalcr = $totalcr + $row['cramount'];
                                        $totaldr = $totaldr + $row['dramount'];
                                        ?>
                                        <tr>
                                            <td><?php echo $row['Name']; ?></td>	
                                            <td><?php echo $row['Code']; ?></td>
                                            <td><?php echo $row['Narration']; ?></td>
                                            <td><?php echo number_format($row['dramount'], 2); ?></td>
                                            <td><?php echo number_format($row['cramount'], 2); ?></td>

                                        </tr>

                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="bg-red text-sm">
                                    <tr>
                                        <td colspan="3">Total DR:</td>
                                        <td><?php echo number_format($totaldr, 2); ?></td>
                                        <td><?php echo number_format($totalcr, 2); ?></td>
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
</div><!-- ./wrapper -->
<?php
include_once 'footer.php';
?>
<script>
    $('#voucher').DataTable({
        
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                filename: 'Subledger-Borrowing Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Subledger-Borrowing Voucher Detail - ' . $Date.') </h5>';?>',
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