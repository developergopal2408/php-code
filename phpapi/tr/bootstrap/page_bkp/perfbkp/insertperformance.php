<?php
include_once 'top.php';
$sqli = "SELECT ID,Name FROM FundAllow WHERE ID = '92' ";
$reso = odbc_exec($connection, $sqli);
$rowa = odbc_fetch_array($reso);
$accid = $rowa['ID'];
$Staffid = $_POST['staffid'];
$sql1 = "SELECT * FROM StaffMain WHERE StaffID = '$Staffid' ";
$res = odbc_exec($connection, $sql1);
$rows = odbc_fetch_array($res);
$branchid = $rows['BranchID'];
$remarks = $_POST['remarks'];

$query = "INSERT INTO SalaryAllowance(StaffID,OfficeID,AccountID,YearMonth,Remarks,PostedBy,IsAllowable,IsChecked) VALUES"
        . "('$Staffid','$branchid','$accid','$fym','$remarks','" . $_SESSION['StaffID'] . "','0','0')";
$run = odbc_exec($connection, $query);
if ($run == TRUE) {
    echo  "<script>alert('Successfully DisAllow Performance ..');window.location='performance.php';</script>";
} else {
   echo "<script>alert('Error Updating Performance ..');window.location='performance.php';</script>"; 
}
?>


