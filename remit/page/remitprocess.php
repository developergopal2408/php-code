<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Kathmandu");
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
    exit;
}
require_once 'db2.php';
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
$_SESSION['CODE'] = $Code;
$_SESSION['branchName'] = $branchName;
mysqli_autocommit($conn, FALSE);
$staffid = $_SESSION['StaffID'];
$branch_name = $_SESSION['branchName'];
$branch_code = $_SESSION['CODE'];
$staff_name = $_SESSION['uname'];
//$staff_code = $_SESSION['Code'];
//$msgid = rand(1, 1000);
$serial = $_POST['serial'];
$remit_no = $_POST['remit_no'];
$rcompany = $_POST['rcompany'];
$rname = $_POST['rname'];
$rfname = $_POST['rfname'];
$raddress = $_POST['raddress'];
$district = $_POST['district'];
$sidtype = $_POST['sidtype'];
$issue = $_POST['issue'];
$idno = $_POST['idno'];
$dob = $_POST['dob'];
$rcontact = $_POST['rcontact'];
$sname = $_POST['sname'];
$scontact = $_POST['scontact'];
$relation = $_POST['relation'];
$country = $_POST['country'];
$expamount = $_POST['expamount'];
$tdate = date('Y-m-d');
$sql = mysqli_query($conn, "INSERT INTO remittance (BCODE,BRANCHNAME,EXPECTEDAMT,RECEIVERADDRESS,RECEIVERCONTACTNO,RECEIVERDISTRICT,RECEIVERDOB,RECEIVERIDISSUEDATE,RECEIVERIDNO,RECEIVERIDTYPE,RECEIVERNAME,RECEIVERFATHERNAME,REMITCOMPANY,REMITNO,SENDERCONTACTNO,SENDERCOUNTRY,SENDERNAME,SENDERRELATION,SERIALNO,STAFFNAME,STAFFID,TDATE,STATUS) VALUES ('$branch_code','$branch_name','$expamount','$raddress','$rcontact', '$district','$dob','$issue','$idno','$sidtype','$rname','$rfname','$rcompany','$remit_no', '$scontact','$country','$sname','$relation','$serial','$staff_name','".$_SESSION['StaffID']."','$tdate','PENDING')") or die(print_r(mysqli_error($conn)));
if ($sql) {
    mysqli_commit($conn);
    echo json_encode(array("status" => "success", "message" => "Your Remittance has been successfully posted"), 200);
} else {
    print_r(mysqli_error($conn));
    mysqli_rollback($conn);
    echo json_encode(array("status" => "error", "message" => "Error Processing your Remittance!"));
}



