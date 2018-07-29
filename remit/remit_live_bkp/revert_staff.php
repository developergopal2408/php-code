<?php

session_start();
include_once 'connect.php';
$staffid = $_GET['staffid'];

$sql = "UPDATE `users` SET IS_APPROVED_BY = '0' where STAFFID='$staffid'"; 
$run = mysqli_query($con, $sql);

if ($run == TRUE) {
   echo  "<script>alert('Your have successfully Change the Position ');window.location = 'manage_users.php';</script> ";
} else {
   echo  "<script>alert('Error while Updating the record');window.location = 'manage_users.php';</script>"; 
}

?>