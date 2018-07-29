<?php
include_once "db.php";
$ID = $_REQUEST['ID'];
$query = "select * from collbalance WHERE ID = '$ID' and IsViewed = '0'";  
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
	$update = "UPDATE collbalance SET IsViewed = '1' WHERE ID = '$ID' ";  
	odbc_exec($connection, $update);
	$response = array('Status' => true, 'Message' => "Success");
	echo json_encode($response,JSON_PRETTY_PRINT);
}else{
	$failure = "No Data Exist..";
	$response = array('Status' => false, 'Message' => $failure);
	echo json_encode($response,JSON_PRETTY_PRINT);
}

?>