<?php

include_once "db.php";
$MemberID = $_REQUEST['MemberID'];
$OfficeID = $_REQUEST['OfficeID'];
$PinCode = $_REQUEST['PinCode'];
$FromDate = $_REQUEST['FromDate'];
$ToDate = $_REQUEST['ToDate'];
$LoanMainID = $_REQUEST['LoanMainID'];
$data = array();
$SavingDetail = [];

$qry = odbc_exec($connection, "select * from member where OfficeID = '$OfficeID' AND MemberID = '$MemberID' AND PinCode = '$PinCode'");
if (odbc_num_rows($qry) > 0) {
    if (($FromDate AND $ToDate) == null) {
        $query = "select Top 10 SaveDate,LoanInsNo,TransType,RefType,Particulars,LoanDr,LoanCr,IntCr from LoanDetail where OfficeID = '$OfficeID' and MemberID = '$MemberID' AND LoanMainID = '$LoanMainID'  ORDER BY SaveDate DESC";
    } else {
        $query = "select  SaveDate,LoanInsNo,TransType,RefType,Particulars,LoanDr,LoanCr,IntCr from LoanDetail where OfficeID = '$OfficeID' and MemberID = '$MemberID' AND LoanMainID = '$LoanMainID' AND SaveDate Between '$FromDate' AND '$ToDate' ORDER BY SaveDate DESC";
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