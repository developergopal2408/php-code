<?php
error_reporting(0);
session_start();
include_once 'connect.php';
if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}


if($branchId = $_GET['branchId']){
$sql = mysqli_query($con,"UPDATE `remittancedetail` SET STATUS='1' where branchId='$branchId'"); 
}else{
    $detailid = $_GET['detailid'];
   $sql = mysqli_query($con,"UPDATE `remittancedetail` SET STATUS='1' where Detailid='$detailid'"); 
}

if ($sql== TRUE) {
    
 echo  "<script>alert('Your Remittance has been successfully Approved and send to Head Office');setTimeout(\"location.href = 'branch_remit.php';\",2500);</script> ";
} else {
   echo  "<script>alert('Error while Updating the record');window.location = 'branch_remit.php';</script>"; 
}


?>