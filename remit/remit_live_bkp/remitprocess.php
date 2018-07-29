<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//error_reporting(0);
date_default_timezone_set('Asia/Kathmandu');
session_start();
require_once 'connect.php';
if (!isset($_SESSION['STAFFID'])) {
    
    header('Location:login.php');
}
$staffid = $_SESSION['STAFFID'];
$branch_name = $_SESSION['BRANCHNAME'];
$branch_code = $_SESSION['BRANCHCODE'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$staff_name = "$fname $lname";
$staff_code = $_SESSION['STAFFCODE'];

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

$sql = mysqli_query($con, "INSERT INTO remittance ("
        . "BCODE,BRANCHNAME,EXPECTEDAMT,PAIDAMT,RECEIVERADDRESS,RECEIVERCONTACTNO,"
        . "RECEIVERDISTRICT,RECEIVERDOB,RECEIVERIDISSUEDATE,RECEIVERIDNO,"
        . "RECEIVERIDTYPE,RECEIVERNAME,RECEIVERFATHERNAME,REMITCOMPANY,REMITNO,"
        . "SENDERCONTACTNO,SENDERCOUNTRY,SENDERNAME,"
        . "SENDERRELATION,SERIALNO,STAFFID,STAFFNAME,TRANSACTIONCODE,TDATE,PAIDDATE,STATUS) VALUES ("
        . " '$branch_code','$branch_name','$expamount','0','$raddress','$rcontact',"
        . " '$district','$dob','$issue','$idno','$sidtype','$rname','$rfname','$rcompany','$remit_no',"
        . " '$scontact','$country','$sname','$relation','$serial','$staff_code','$staff_name',"
        . " '0','$tdate','0000-00-00','PENDING')") or die(print_r(mysqli_error($con)));

if ($sql) {
    echo json_encode(array("status" => "success", "message" => "Your Remittance has been successfully posted"), 200);
    exit;
} else {
	print_r(mysqli_error($con));
    echo json_encode(array("status" => "error", "message" => "Error Processing your Remittance!"));
    exit;
}
$con->close();


