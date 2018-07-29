<?php

include_once "db.php";
header('Content-Type: application/json');
$MemberMessage = [];
$getdata = array();

$query = "select  Top 10 *  from MemberMessage ORDER BY MsgID DESC";
$result = odbc_exec($connection, $query);
if (odbc_num_rows($result) >= 0) {
    while ($rows = odbc_fetch_array($result)) {
        $MemberMessage [] = array(
            "MsgID" => $rows['MsgID'],
            "Message" => htmlentities($rows['Message'], ENT_IGNORE, "UTF-8"),
            "Subject" => htmlentities($rows['Subject'], ENT_IGNORE, "UTF-8"),
            "PostedBy" => $rows['PostedBy']
        );
    }
    $getdata = $MemberMessage;
	
    if ($getdata != null) {
        $response["Status"] = "Success";
        $response["data"] = $getdata;
    } else {
        $response["Status"] = "Success";
        $response["data"] = "No data";
    }

    $endoded = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $endoded;
} else {
	$failure = "तपाइको पासवोर्ड परिवर्तन भएको छ | कृपया लग आउट गरि पुन लग इन गर्नुहोला |";
    $response = array('Status' => false, 'Message' => $failure);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>