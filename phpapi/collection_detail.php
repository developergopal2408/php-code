<?php

include_once "db.php";
$MemberID = $_REQUEST['MemberID'];
$OfficeID = $_REQUEST['OfficeID'];
$PinCode = $_REQUEST['PinCode'];
$FromDate = $_REQUEST['FromDate'];
$ToDate = $_REQUEST['ToDate'];
$IsViewed = $_REQUEST['IsViewed'];
$data = array();
$SavingDetail = [];

$qry = odbc_exec($connection, "select * from member where OfficeID = '$OfficeID' AND MemberID = '$MemberID' AND PinCode = '$PinCode'");
if (odbc_num_rows($qry) > 0) {
    if ($IsViewed == 0) {
        $query = "select d.ID,d.IsViewed,m.MasterID,m.SaveDate,d.Deposit,d.ExpectedAmt,d.NetAmount,d.Withdraw,d.IsPaid,d.IsPresent,d.Balance 
                from collmaster m,collbalance d 
                where m.MasterID = d.MasterID and d.MemberID = '$MemberID' and m.OfficeID = '$OfficeID' and m.IsUp = '1'
                and d.IsViewed = '0'
                order by m.SaveDate DESC ";
    } else {
        if (($FromDate AND $ToDate) == null) {
            $query = "select Top 10 d.ID,d.IsViewed,m.MasterID,m.SaveDate,d.Deposit,d.ExpectedAmt,d.NetAmount,d.Withdraw,d.IsPaid,d.IsPresent,d.Balance 
                from collmaster m,collbalance d 
                where m.MasterID = d.MasterID and d.MemberID = '$MemberID' and m.OfficeID = '$OfficeID' and m.IsUp = '1'
                order by m.SaveDate DESC ";
        } else {
            $query = "select d.ID,d.IsViewed,m.MasterID,m.SaveDate,d.Deposit,d.ExpectedAmt,d.NetAmount,d.Withdraw,d.IsPaid,d.IsPresent,d.Balance 
                from collmaster m,collbalance d 
                where m.MasterID = d.MasterID and d.MemberID = '$MemberID' and m.OfficeID = '$OfficeID' and m.IsUp = '1'
                and m.SaveDate Between '$FromDate' and '$ToDate'
                order by m.SaveDate DESC ";
        }
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
        $response["data"] = $data;
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