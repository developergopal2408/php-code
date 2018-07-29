<?php
include_once 'top.php';
$qry1 = "select max(dayend)date from dayend where officeid = '".$_SESSION['BranchID']."' ";
$rdate = odbc_exec($connection, $qry1);
$maxdate = odbc_fetch_array($rdate);
$dayenddate = $maxdate['date'];
$fym1 = substr($dayenddate, 0,7);
$sqli = "SELECT ID,Name FROM FundAllow WHERE ID = '92' ";
$reso = odbc_exec($connection, $sqli);
$rowa = odbc_fetch_array($reso);
$accid = $rowa['ID'];
//$Staffid = $_POST['sid'];
$id = $_POST['id'];
$sql1 = "SELECT * FROM StaffMain WHERE StaffID = '$Staffid' ";
$res = odbc_exec($connection, $sql1);
$rows = odbc_fetch_array($res);
$branchid = $rows['BranchID'];
$remarks = $_POST['remarks'];

$query = "UPDATE SalaryAllowance SET IsAllowable = '1',IsChecked = '1',IsCheckedBy = '".$_SESSION['StaffID']."',Remarks = '$remarks' WHERE ID = '$id' AND YearMonth = '$fym1'";
$run = odbc_exec($connection, $query);
if ($run == TRUE) {
    echo  "<script>alert('Successfully Allowed Performance ..');window.location='performance.php';</script>";
} else {
   echo "<script>alert('Error Updating Performance ..');window.location='performance.php';</script>"; 
}
?>


