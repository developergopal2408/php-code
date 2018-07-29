<?php

include_once "db.php";
$MemberID = $_REQUEST['MemberID'];
$OfficeID = $_REQUEST['OfficeID'];
$PinCode = $_REQUEST['PinCode'];

$data = "";
$SavingDetail = "";

$qry = odbc_exec($connection, "select * from member where OfficeID = '$OfficeID' AND MemberID = '$MemberID' AND PinCode = '$PinCode'");
if (odbc_num_rows($qry) > 0) {

    $query = "select count(*)total from CollBalance c,CollMaster m where c.MasterID = m.MasterID and  c.IsViewed = 0 
                  and c.MemberID = '$MemberID' and m.OfficeID = '$OfficeID'";

    $result = odbc_exec($connection, $query);

    if (odbc_num_rows($result) > 0) {
        $row = odbc_fetch_array($result);
        $SavingDetail = $row['total'];
        $data = $SavingDetail;
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