<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//error_reporting(0);
session_start();
require_once 'connect.php';
if (!isset($_SESSION['STAFFID'])) {
    
    header('Location:login.php');
}

$staffid = $_SESSION['STAFFID'];


$msgid = $_GET['msg_id'];
$branch_name = $_SESSION['BRANCHNAME'];
$branch_code = $_SESSION['BRANCHCODE'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$staff_name = "$fname $lname";
$staff_code = $_SESSION['STAFFCODE'];




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
$expamount =  $_POST['expamount'];
$tdate = date('Y-m-d H:i:s');


/*$sql = mysqli_query($con, "INSERT INTO `remittance_detail` (serial, remit_no, rcompany, rname, rfname, raddress, district, sidtype, issue, idno, dob, rcontact, sname, scontact, relation, country, expamount, tdate, status,STAFFNAME,STAFFCODE,BRANCHCODE,BRANCHNAME)
VALUES('$serial','$remit_no','$rcompany','$rname','$rfname','$raddress','$district','$sidtype', '$issue','$idno','$dob','$rcontact','$sname','$scontact','$relation','$country','$expamount','$tdate','Pending','$staff_name','$staff_code','$branch_code','$branch_name')");*/

$sql = "UPDATE `remittance` SET BCODE = '$branch_code',BRANCHNAME = '$branch_name',EXPECTEDAMT = '$expamount',RECEIVERADDRESS = '$raddress',RECEIVERCONTACTNO = '$rcontact',RECEIVERDISTRICT = '$district',RECEIVERDOB = '$dob',RECEIVERIDISSUEDATE = '$issue',RECEIVERIDNO = '$idno',RECEIVERIDTYPE = '$sidtype',RECEIVERNAME = '$rname',RECEIVERFATHERNAME = '$rfname',REMITCOMPANY = '$rcompany',REMITNO = '$remit_no',SENDERCONTACTNO = '$scontact',SENDERCOUNTRY = '$country',SENDERNAME = '$sname',SENDERRELATION = '$relation',SERIALNO = '$serial',STATUS = 'PENDING' WHERE MSGID='$msgid'";
$run = mysqli_query($con, $sql);


if ($run === TRUE) {
    echo json_encode(array("status" => "success", "message" => "Your Remittance has been successfully Updated And Resend to HQ"), 200);
    exit;
} else {
    echo json_encode(array("status" => "error", "message" => "Error Updating your Remittance!"));
    exit;
}
$con->close();


