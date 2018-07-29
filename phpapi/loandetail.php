<?php

include_once "db.php";
$MemberID = $_REQUEST['MemberID'];
$OfficeID = $_REQUEST['OfficeID'];
$PinCode = $_REQUEST['PinCode'];
$data = array();
$SavingDetail = [];

$qry = odbc_exec($connection, "select * from member where OfficeID = '$OfficeID' AND MemberID = '$MemberID' AND PinCode = '$PinCode'");
if (odbc_num_rows($qry) > 0) {
    $query = "select l.LoanTypeID, m.LoanNo,l.LoanMainID,l.LoanHeadingID,(select LoanType From LoanType where LoanTypeID = l.LoanTypeID)LoanType,
	(select LoanHeading From LoanHeading where LoanHeadingID = l.LoanHeadingID)LoanHeading,
	sum(l.LoanDr - l.LoanCr)Balance, m.IssueDate,m.InstAmount,m.MaturityDate
from LoanDetail l ,LoanMain m where l.loanmainID=m.loanmainID and l.OfficeID=m.OfficeID and  l.OfficeID = '$OfficeID' and l.MemberID = '$MemberID'
group by l.LoanTypeID,m.LoanNo,l.LoanMainID, m.IssueDate,l.LoanHeadingID,m.InstAmount,m.MaturityDate
having sum(l.LoanDr - l.LoanCr) > 0";
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