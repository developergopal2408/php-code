<?php
include_once "db.php";

$id = $_REQUEST['officeid'];
$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];
$sql = "SELECT * FROM OfficeDetail WHERE ID='$id' ";
$res = odbc_exec($connection,$sql);
$p = odbc_fetch_array($res);
$pid = $p['ID']; 	


$daybook = array();
$response = array();


$query = "select m.Membercode,m.Firstname+' '+m.lastname as MemberName,m.Spousefather,m.Fatherinlaw,(l.issuedate)Date,t.loantype,l.intrate,(i.intcroption)Tnstype,(l.installementno)InsNo,l.loanamount
from member m, loanmain l, loantype t,intcroptionloan i
where m.memberid=l.memberid and l.loantypeid=t.loantypeid and i.intcroptionid=l.intcroptionid and l.issuedate between '$date1' and '$date2'
and m.officeid=l.officeid and m.officeid='$pid'
group by m.Membercode,m.Firstname,m.lastname,m.Spousefather,m.Fatherinlaw,l.issuedate,t.loantype,l.intrate,i.intcroption,l.installementno,l.loanamount order by m.membercode";

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