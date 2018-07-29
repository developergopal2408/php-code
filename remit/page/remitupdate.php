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
$msgid = $_GET['msg_id'];
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
$tdate = date('Y-m-d H:i:s');
$sql = "UPDATE `remittance` SET "
        . "BCODE = '$branch_code',BRANCHNAME = '$branch_name',EXPECTEDAMT = '$expamount',"
        . "RECEIVERADDRESS = '$raddress',RECEIVERCONTACTNO = '$rcontact',RECEIVERDISTRICT = '$district',"
        . "RECEIVERDOB = '$dob',RECEIVERIDISSUEDATE = '$issue',RECEIVERIDNO = '$idno',RECEIVERIDTYPE = '$sidtype',"
        . "RECEIVERNAME = '$rname',RECEIVERFATHERNAME = '$rfname',REMITCOMPANY = '$rcompany',REMITNO = '$remit_no',"
        . "SENDERCONTACTNO = '$scontact',SENDERCOUNTRY = '$country',SENDERNAME = '$sname',"
        . "SENDERRELATION = '$relation',SERIALNO = '$serial',STAFFID = '$staffid',STATUS = 'PENDING' "
        . "WHERE "
        . "MSGID='$msgid'";
$run = mysqli_query($conn, $sql);
if ($run === TRUE) {
    mysqli_commit($conn);
    echo json_encode(array("status" => "success", "message" => "Your Remittance has been successfully Updated And Resend to HO"), 200);
    exit;
} else {
    print_r(mysqli_error($conn));
    mysqli_rollback($conn);
    echo json_encode(array("status" => "error", "message" => "Error Updating your Remittance!"));
    exit;
}
$con->close();


