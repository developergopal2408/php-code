<?php

include_once 'top.php';
//$password = "anything";
$pass = $_POST['pass'];
 if ($_SESSION['BranchID'] == '1' AND $_SESSION['StaffID'] == '18' AND $_SESSION['pass'] == md5($pass)) {
    echo"<script>alert('Password Verified');location.href = 'noperformance.php';</script>";
} else {
    echo"<script>alert('Error Password');location.href = 'dashboard.php';</script>";
}
?>

