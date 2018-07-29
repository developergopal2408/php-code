<?php
ob_start();
session_start();
require_once 'db.php';
$staffid = $_GET['staffid'];
$sql =  "select * from staffmain where staffid = '$staffid' AND JobTypeID != '2' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$dbstaffcode = $row['Code'];
$dbmobile = $row['Mobile'];
$StaffID = $row['StaffID'];
$fname = $row['FirstName'];
$lname = $row['LastName'];
$jid = $row['JobTypeID'];
$dbpass = $row['Password'];
$branchid = $row['BranchID'];
$Photo = $row['Photo'];
$departid = $row['DepartmentID'];
$_SESSION['Code'] = $dbstaffcode;
$_SESSION['StaffID'] = $StaffID;
$_SESSION['uname'] = $fname ." ". $lname;
$_SESSION['BranchID'] = $branchid;
$_SESSION['JobTypeID'] = $jid;
$_SESSION['pass'] = $dbpass;
$_SESSION['Photo'] = $Photo;
$_SESSION['DepartmentID'] = $departid;

header('Location:page/dashboard.php');
?>

