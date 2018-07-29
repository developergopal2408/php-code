<?php

include_once 'top.php';
$qry1 = "select max(dayend)date from dayend where officeid = '" . $_SESSION['BranchID'] . "' ";
$rdate = sqlsrv_query($connection, $qry1);
$maxdate = sqlsrv_fetch_array($rdate);
$dayenddate = $maxdate['date'];
$fym1 = substr($dayenddate, 0, 7);
$sqli = "SELECT ID,Name FROM FundAllow WHERE ID = '92' ";
$reso = sqlsrv_query($connection, $sqli);
$rowa = sqlsrv_fetch_array($reso);
$accid = $rowa['ID'];
$Staffid = $_POST['staffid'];
$sql1 = "SELECT * FROM StaffMain WHERE StaffID = '$Staffid' ";
$res = sqlsrv_query($connection, $sql1);
$rows = sqlsrv_fetch_array($res);
$branchid = $rows['BranchID'];
$remarks = $_POST['remarks'];

$q2 = "select * from SalaryAllowance where YearMonth = '$fym1' AND IsAllowable = '1' AND IsChecked = '1' AND StaffID = '$Staffid'";
$r2 = sqlsrv_query($connection, $q2);
$run1 = sqlsrv_fetch_array($r2);
if($run1['StaffID'] == $Staffid){
    $query = "UPDATE SalaryAllowance SET IsAllowable = '0',IsChecked = '0',PostedBy = '".$_SESSION['StaffID']."',Remarks = '$remarks',IsCheckedBy = '' WHERE ID = '".$run1['ID']."' AND STAFFID = '$Staffid' AND YearMonth = '$fym1'";
}else{
$query = "INSERT INTO SalaryAllowance(StaffID,OfficeID,AccountID,YearMonth,Remarks,PostedBy,IsAllowable,IsChecked) VALUES"
        . "('$Staffid','$branchid','$accid','$fym1','$remarks','" . $_SESSION['StaffID'] . "','0','0')";
}
$run = sqlsrv_query($connection, $query);
if ($run == TRUE) {
    echo "<script>alert('Successfully DisAllow Performance ..');window.location='performance.php';</script>";
} else {
    echo "<script>alert('Error Updating Performance ..');window.location='performance.php';</script>";
}
?>


