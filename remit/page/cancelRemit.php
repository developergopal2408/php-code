<?php
//error_reporting(0);
session_start();
include_once 'db2.php';
$msg_id = $_GET['msg_id'];
$comment = $_POST['reason'];
$transaction_code = rand(1, 1000);
$date = date('Y-m-d');
$sql = mysqli_query($conn,"UPDATE `remittance` SET PAIDAMT='0',STATUS='REJECTED',REASON='$comment',TRANSACTIONCODE='$transaction_code',PAIDDATE='$date' where MSGID='$msg_id'"); 
if ($sql== TRUE) {
    echo json_encode(array("status"=>"success","message"=> "Your Remittance has been  Rejected "), 200); exit;
} else {
   echo json_encode(array("status"=>"error","message"=> "Error")); exit;
}
$conn->close();

?>