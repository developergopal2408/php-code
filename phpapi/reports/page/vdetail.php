<?php
ob_start();
session_start();
require_once '../db.php';
include_once 'header.php';

$Vno = $_REQUEST['Vno'];
$Date = $_REQUEST['Date'];

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
                Sub-Ledger Detail's Page
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
                             <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#voucher').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                             </div>

                           
                        </div>

                        <div class="box-body">
                           
                            <table id="voucher" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red">
                                    <tr>

                                        <th>Account Head</th>
                                        <th>Code No.</th>
                                        <th>Description</th>
                                       <th>Dr.Amount</th>
                                        <th>Cr.Amount</th>
                                        
                                    </tr>

                                </thead>
								 <tbody>
										<?php
										$totalcr = 0;
										$totaldr = 0;
										
															$query = "select a.name as Name,l.ldate,v.Narration,v.vno,l.dramount,l.cramount,(a.LF) as Code
																	  from ledger l, vouchermaster v,acctree a
																	  where l.vno=v.id and l.ldate='$Date' and v.vno='".intval($Vno)."' and a.id = l.accountheadid
																	  order by l.ldate";								
															$detail = odbc_exec($connection,$query);
															
															while ($row = odbc_fetch_array($detail)) {
																$totalcr = $totalcr + $row['cramount'];
																$totaldr = $totaldr + $row['dramount'];
																?>
																<tr>
																	<td><?php echo $row['Name']; ?></td>	
																	<td class="col-xs-2"><?php echo $row['Code']; ?></td>
																	<td class="col-xs-3"><?php echo $row['Narration']; ?></td>
																	<td class="col-xs-2 text-right"><?php echo number_format($row['dramount'],2); ?></td>
																	<td class="col-xs-2 text-right"><?php echo number_format($row['cramount'],2); ?></td>
																						
																</tr>
																
																<?php
															}
															?>
                                  
                                </tbody>


                            </table>
							
							</div>
							
							<div class="box-footer">
							  <div class="row">

                <div class="col-xs-6 pull-right">
                    

                    <div class="table-responsive">
                        <table class="table">
                           
                            <tr>
							<th></th>
							<th></th>
                            <th>Total DR:</th>
                                <td><b><?php echo number_format($totaldr, 2); ?></b></td>
								
                                <td><b><?php echo number_format($totalcr, 2); ?></b></td>
                            </tr>
                           
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
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
