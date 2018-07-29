<?php
include_once "db.php";
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

if ($obj != null) {
 $date = date('Y-m-d H:i:s');
$query = odbc_exec($connection,"select * from member where OfficeID = '" . $obj['OfficeID'] . "' AND MemberID = '" . $obj['MemberID'] . "' AND PinCode = '" . $obj['PinCode'] . "' ");
if(odbc_num_rows($query) > 0){
	$update = "INSERT INTO MemberQueries( Message, MemberID, OfficeID, PostDateTime) VALUES ('" . $obj['Message'] . "', '" . $obj['MemberID'] . "', '" . $obj['OfficeID'] . "','$date') ";  
	odbc_exec($connection, $update);
	$response = array('Status' => true, 'Message' => "Success");
	echo json_encode($response,JSON_PRETTY_PRINT);
}else{
	$failure = "तपाइको पासवोर्ड परिवर्तन भएको छ | कृपया लगाउट गरि पुन लागिन जर्नुहोला |";
    $response = array('Status' => false, 'Message' => $failure);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
}else{
	$failure = "कृपया खाली ठाउँ भर्नुहोस् |";
	$response = array('Status' => false, 'Message' => $failure);
	echo json_encode($response,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

?>