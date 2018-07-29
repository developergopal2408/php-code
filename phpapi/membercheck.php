<?php

include_once "db.php";
$RegNo = $_REQUEST['RegNo'];
$MobileNo = $_REQUEST['MobileNo'];
$data = array();
$MemberInfo = [];
$failure = "";

$rand = rand(1000, 9999);

function sendSMS($content) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://beta.thesmscentral.com/api/v3/sms?");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);
    return $server_output;
}

$token = 'BHgpegb0Q1ejrHCl641jNHBMNBb7dtiWBLJ';
$to = $MobileNo;
$sender = 'JBS_ALERT';
$message = 'Your OTP - '.$rand;

// set post fields
$content = [
    'token' => rawurlencode($token),
    'to' => rawurlencode($to),
    'sender' => rawurlencode($sender),
    'message' => rawurlencode($message),
];


$update = odbc_exec($connection, "UPDATE member SET PinCode = '$rand' WHERE RegNo = '$RegNo' ");
$query = "select * from member where  RegNo = '$RegNo' AND MobileNo = '$MobileNo' AND Status != 'DROPOUT'";
$result = odbc_exec($connection, $query);



if (odbc_num_rows($result) > 0) {

    $row = odbc_fetch_array($result);
    $RegNo = $row['RegNo'];
    $MobileNo = $row['MobileNo'];
    $PinCode = $row['PinCode'];
    sendSMS($content);

    $MemberInfo = array(
        'RegNo' => $RegNo,
        'MobileNo' => $MobileNo,
        'PinCode' => $PinCode
    );
    $data = $MemberInfo;
    if ($data != null) {
        $response["Status"] = "Success";
		$response["Message"] = "कृपया ओटिपि न आएको खण्डमा यो नम्बरमा सम्पर्क गर्नुहोला - ९८०२७९६०३७/९८०२७९६०८३";
        $response["data"] = $data;
    } else {
        $response["Status"] = "Success";
        $response["data"] = "No data Available";
    }
    header('Content-Type: application/json');
    $endoded = json_encode($response, JSON_PRETTY_PRINT);
    echo $endoded;
} else {
    $failure = " तपाइले हालेको मोबाइल नम्बर अथवा दर्ता नम्बर मिलेन | पुन प्रयास गर्नुहोस |";
	
    $response = array('Status' => false, 'Message' => $failure);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>