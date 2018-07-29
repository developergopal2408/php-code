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


$query = "select o.code,o.name,s.code,s.firstname+' '+s.lastname as staffname,c.centercode,m.meetingdate,m.posttime,m.defaultmeetingdate,m.isdown,m.downtime,m.isup,m.uptime,m.meetingcount,m.loancount,m.isgenerated,m.isposted
from officedetail o,staffmain s,centermain c,collmaster m 
where o.id = s.branchid and o.id = c.officeid and o.id = m.officeid and s.staffid = m.staffid and s.staffid = c.staffid and c.centerid = m.centerid and c.officeid = '$pid' and m.meetingdate BETWEEN '$date1' and '$date2'
order by o.code,s.code,c.centercode";

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