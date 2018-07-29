<?php
ob_start();
session_start();
require_once 'db.php';
$staffid = $_GET['staffid'];
$sql =  "select * from staffmain where staffid = '$staffid' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$dbstaffcode = $row['Code'];
$dbmobile = $row['Mobile'];
$StaffID = $row['StaffID'];
$fname = $row['FirstName'];
$lname = $row['LastName'];
$branchid = $row['BranchID'];
$_SESSION['Code'] = $dbstaffcode;
$_SESSION['StaffID'] = $StaffID;
$_SESSION['uname'] = $fname ." ". $lname;
$_SESSION['BranchID'] = $branchid;
header('Location:page/subledger-borrowing.php');
?>

