<?php

include_once "db.php";
$MemberID = $_REQUEST['MemberID'];
$OfficeID = $_REQUEST['OfficeID'];
$PinCode = $_REQUEST['PinCode'];
$SavingTypeID = $_REQUEST['SavingTypeID'];
$FromDate = $_REQUEST['FromDate'];
$ToDate = $_REQUEST['ToDate'];
$data = array();
$SavingDetail = [];

$qry = odbc_exec($connection, "select * from member where OfficeID = '$OfficeID' AND MemberID = '$MemberID' AND PinCode = '$PinCode'");
if (odbc_num_rows($qry) > 0) {
    if (($FromDate AND $ToDate) == null) {
        $query = "select Top 10 SaveDate,TransType,RefType,Particulars,Remarks,CrAmount,DrAmount from SavingDetail where OfficeID = '$OfficeID' and MemberID = '$MemberID' and SavingTypeID = '$SavingTypeID' AND TransType <> 'Provision'  Order By SaveDate DESC";
    } else {
        $query = "select SaveDate,TransType,RefType,Particulars,Remarks,CrAmount,DrAmount from SavingDetail where OfficeID = '$OfficeID' and MemberID = '$MemberID' and SavingTypeID = '$SavingTypeID' AND TransType <> 'Provision'  and SaveDate Between '$FromDate' and '$ToDate'  Order By SaveDate DESC";
    }

    $result = odbc_exec($connection, $query);

    if (odbc_num_rows($result) > 0) {
        while ($row = odbc_fetch_array($result)) {
            $SavingDetail [] = $row;
        }
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