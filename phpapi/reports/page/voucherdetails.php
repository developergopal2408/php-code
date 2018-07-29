<?php
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
include_once 'header.php';
?>


<!-- Site wrapper -->
<div class="wrapper">

    <?php
    include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
    include_once 'sidebar.php'; //Include Sidebar.php-->
	
	$sql = "SELECT * FROM OfficeDetail WHERE ID='".$_SESSION['BranchID']."' ";
    $res = odbc_exec($connection,$sql);
	
	$row = odbc_fetch_array($res);
	$branchName = $row['Name'];
	
	$id = $_REQUEST['ID'];

	
    ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               <i class="fa fa-building"></i> <?php echo $branchName;?>
				
                <small>Voucher Report</small>
            </h1>
			
			
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Voucher List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
			<div class="row">
				
				
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
