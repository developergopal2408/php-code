<?php

include_once "db.php";
$MemberID = $_REQUEST['MemberID'];
$OfficeID = $_REQUEST['OfficeID'];
$PinCode = $_REQUEST['PinCode'];
$Year = $_REQUEST['Year'];
$data = array();
$OfficeDetail = [];

$qry = odbc_exec($connection, "select * from member where OfficeID = '$OfficeID' AND MemberID = '$MemberID' AND PinCode = '$PinCode'");
if (odbc_num_rows($qry) > 0) {
    $query = "select OfficeID,DateVS from OfficeCalendar where OfficeID = '$OfficeID' and DateVS Like '$Year%' Order By DateVS DESC";
    $result = odbc_exec($connection, $query);
    if (odbc_num_rows($result) > 0) {
        while ($row = odbc_fetch_array($result)) {
            $OfficeDetail [] = $row;
        }
        $data = $OfficeDetail;
    }
    if ($data != null) {
        $response["Status"] = "Success";
        $response["data"] = $data;
    } else {
        $response["Status"] = "Success";
        $response["data"] = "No data";
    }
    header('Content-Type: application/json');
    $endoded = json_encode($response, JSON_PRETTY_PRINT);
    echo $endoded;
} else {
    $failure = "तपाइको पासवोर्ड परिवर्तन भएको छ | कृपया लग आउट गरि पुन लग इन गर्नुहोला |";
    $response = array('Status' => false, 'Message' => $failure);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>