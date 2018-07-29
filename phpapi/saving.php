<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 180);
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(180);
ini_set('max_execution_time', 500);
include_once "db.php";

$id = $_REQUEST['officeid'];
$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];
if ($id>0){
	$test="and a.OfficeID=".$id;
} else{
	$test='';
}

$data=[];
       
$query = "select o.code,o.name, a.SaveDate, sum(a.cramount)cr, sum(a.dramount)dr, sum(a.PreBal)sdue  from officedetail o, SavingDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data= $return;

if ($data!= null){
			$response= $data;
        } else{
			
			$response= "No data";	
		}
header('Content-Type: application/json');
$endoded = json_encode($response, JSON_PRETTY_PRINT);
echo $endoded;
?>