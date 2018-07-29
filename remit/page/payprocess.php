<?php

ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
include_once 'db2.php';
if (isset($_POST['submit'])) {
    require_once('../js/nepali_calendar.php');
    require_once('../js/functions.php');
    $cal = new Nepali_Calendar();
    list($year, $month, $day) = explode('-', date('Y-m-d'));
    $nepdate = $cal->eng_to_nep($year, $month, $day);
    $nyr = $nepdate['year'];
    $nmonth = $nepdate['month'];
    $nday = $nepdate['date'];
    $cdate = $nyr . "-" . $nmonth . "-" . $nday;
    $remitco = $_POST['remitco'];
    $remitno = $_POST['remitno'];
    $amount = $_POST['amount'];
    $sql = mysqli_query($conn, "INSERT INTO remittancedetail(RemitID,branchId,Savedate,Amount,STAFFID,REMITNO,STATUS)VALUES('$remitco','" . $_SESSION['BranchID'] . "','$cdate','$amount','" . $_SESSION['StaffID'] . "','$remitno','0')");
    echo "<script>alert('You Have Successfully Paid Remit..');location.href='pay_remit.php'</script>";
}
?>