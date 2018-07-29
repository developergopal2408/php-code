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
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-dashboard"></i> Dashboard
                <small>It's all starts here</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
		
		<div class="pad margin no-print">
            <div class="callout callout-danger  " >
                <i class="fa fa-info"></i><b> कृपया तल दियिएको निर्देश राम्ररी पढ्नु होला |</b><hr>
                <p><b> 1. रेमिट केन्द्रिय कार्यालय पठाउन Request Remit ओप्शनमा क्लिक गर्नुहोस |</b></p>
                <p><b> 2. रेमिट शाखा बाट नै तिरेको खण्डमा Pay Remit क्लिक गर्नुहोस  |</b></p>
                <p><b> 3. रेमिट शाखा बाट बाहिर पठाएको खण्डमा Send Remit क्लिक गर्नुहोस   |</b></p>
            </div>
        </div>
        <!-- Main content -->
        <section class="content"></section>
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
