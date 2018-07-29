<?php
include_once "db.php";

$officeid = $_REQUEST['officeid'];
$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];

$daybook = array();
$response = array();


$query = "SELECT m.MemberCode As Code, M.FirstName, M.LastName, m.SpouseFather, m.FatherInLaw,  lt.LoanType, d.LoanNo, l.NetCash, l.NetWorth, l.ApprovedDate, l.ApprovedLoan,
(SELECT LoanHeading FROM LoanHeading WHERE LoanHeadingID=l.LoanHeadingID) AS LoanHeading, d.SaveDate AS DemandDate, l.DemandLoan, l.AnalyzedDate,
(SELECT SUM(CrAmount-DrAmount) FROM SavingDetail WHERE OfficeID=l.OfficeID AND MemberID=l.MemberID and SavingTypeID<>1) AS SavingBalance,
(SELECT SUM(LoanDr-LoanCr) FROM LoanDetail WHERE OfficeID=l.OfficeID AND MemberID=l.MemberID) AS LoanBalance,
(SELECT Code FROM StaffMain WHERE StaffID=l.UserID) AS SubBy FROM Member m, DemandLoan d, AnalysisLoan l, LoanType lt
WHERE m.MemberID=l.MemberID AND l.LoanTypeID=d.LoanTypeID AND l.LoanTypeID=lt.LoanTypeID AND l.DemandLoanID=d.DemandLoanID
AND m.OfficeID=l.OfficeID AND d.OfficeID=l.OfficeID AND l.MemberID=d.MemberID AND l.Status='A'
AND l.AnalyzedDate BETWEEN '$date1' and '$date2' AND m.OfficeID = '$officeid' 
GROUP BY m.MemberCode, M.FirstName,M.LastName,l.OfficeID, d.LoanNo, l.NetCash, l.NetWorth, l.ApprovedDate, l.ApprovedLoan, l.MemberID, l.UserID, l.LoanHeadingID, m.SpouseFather, m.FatherInLaw,  
lt.LoanType,d.SaveDate, l.DemandLoan, l.AnalyzedDate ORDER BY l.AnalyzedDate,l.UserID, m.MemberCode";
            
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$daybook ['Data'][] = $day;
	$response["Status"]="Success";
	$response["responseText"]=$daybook;
}
header('Content-Type: application/json');
if($result){
$endoded= json_encode($response,JSON_PRETTY_PRINT);
	echo $endoded;
}else {
	$response["Status"]="Failed";
	$response["responseText"]="No Data Feeded";
	$endoded= json_encode($response,JSON_PRETTY_PRINT);
	echo $endoded;
	}
	}else{
	$daybook ['Data'][] = "No Data";
	$response["Status"]="Success";
	$response['responseText'] = $daybook;
	
	echo json_encode($response,JSON_PRETTY_PRINT);
}
?>