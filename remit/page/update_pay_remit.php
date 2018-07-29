<?php
ob_start();
session_start();
error_reporting(0);
include_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
    exit;
}
include_once 'db2.php';
$branchId = $_GET['branchId'];
$detailid = $_GET['detailid'];
if ($branchId) {
    $sql = mysqli_query($conn, "UPDATE remittancedetail SET STATUS='1' where branchId='$branchId'")or die(print mysqli_error($conn));
} else {
   
    $sql = mysqli_query($conn, "UPDATE remittancedetail SET STATUS='1' where Detailid='$detailid'") or die(print mysqli_error($conn));
}
if ($sql == true) {
    echo "<script>alert('Your Remittance has been successfully Approved and send to Head Office');setTimeout(\"location.href = 'pay_remit.php';\",2500);</script> ";
} else {
    echo "<script>alert('Error while Updating the record');window.location = 'pay_remit.php';</script>";
}
?>