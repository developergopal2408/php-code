<?php
include_once 'top.php';
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

$query = "UPDATE SalaryAllowance SET IsAllowable = '1',IsChecked = '1',IsCheckedBy = '".$_SESSION['StaffID']."',Remarks = '$remarks' WHERE ID = '$id' AND YearMonth = '$fym'";
$run = odbc_exec($connection, $query);
if ($run == TRUE) {
    echo  "<script>alert('Successfully Allowed Performance ..');window.location='performance.php';</script>";
} else {
   echo "<script>alert('Error Updating Performance ..');window.location='performance.php';</script>"; 
}
?>


