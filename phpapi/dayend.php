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

$sql = "SELECT f.FBsYear FROM FisCal f, OfficeDetail a WHERE a.ID=1 and a.FyearID=f.FyearID";
$res = odbc_exec($connection,$sql);
$p = odbc_fetch_array($res);
$fiscal = $p['FBsYear']; 
$fyear=substr($fiscal,0,4);
$fyearn=$fyear."/04/01";

$data=[];
       
$query = "select o.code,o.name, max(a.dayend) as DayEnd,
(select sum(dramount-cramount)from ledger where o.id=officeid and ldate between '$fyearn' and  max(a.dayend) and officeid=a.officeid and accountheadid=301)cash
from officedetail o, dayend a where o.id=a.officeid  $test group by o.code,o.name,o.id,a.officeid order by  a.dayend DESC";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
	}
	$data= $return;
}

if ($data!= null){
			$response = $data;
        } else{
			$response= "No data";	
		}
header('Content-Type: application/json');
$endoded = json_encode($response, JSON_PRETTY_PRINT);
echo $endoded;
?>