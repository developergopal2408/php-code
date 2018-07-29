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

$data= array();
       
$query = "select o.code,o.name, max(a.dayend) as DayEnd,
(select sum(dramount-cramount)from ledger where o.id=officeid and ldate between '$fyearn' and  max(a.dayend) and officeid=a.officeid and accountheadid=301)cash
from officedetail o, dayend a where o.id=a.officeid  $test group by o.code,o.name,o.id,a.officeid order by  a.dayend DESC";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
$data['dayend'] = $return;
}


$query = "select o.id, o.code,o.name, count(a.MasterId) as nos, a.MeetingDate from officedetail o, CollMaster a where o.id=a.officeid  $test and a.MeetingDate between '$date1' and '$date2'
group by o.id, o.code,o.name, a.MeetingDate order by a.MeetingDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['meeting'] = $return;

$query = "select o.Code,o.Name, a.SaveDate, sum(a.cramount)cr, sum(a.dramount)dr from officedetail o, SavingDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['saving'] = $return;

$query = "select o.Code,o.Name, a.SaveDate, sum(a.PreBal)sdue  from officedetail o, SavingDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate having sum(a.PreBal)>0 order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['sdue'] = $return;

$query = "select o.Code,o.Name, a.SaveDate, sum(a.LoanCr)pri, sum(a.IntCr)int from officedetail o, LoanDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate having sum(a.LoanCr+a.IntCr) >0 order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['loan'] = $return;

$query = "select o.Code,o.Name, a.SaveDate, sum(a.loandr) loandr from officedetail o, LoanDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate having sum(a.loandr)>0 order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['loandr'] = $return;


$query = "select o.Code,o.Name, a.SaveDate, sum(a.pridue)pridue, sum(a.intdue)intdue from officedetail o, LoanDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate having sum(a.pridue+a.intdue) >0 order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['ldue'] = $return;



$query = "select o.Code,o.Name, a.SaveDate, sum(a.cramount)cr, sum(a.dramount)dr from officedetail o, InsuranceDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['fund'] = $return;

$query = "select o.Code,o.Name, a.SaveDate, sum(a.PreBal)idue from officedetail o, InsuranceDetail a 
where o.id=a.officeid  $test  and a.SaveDate between '$date1' and '$date2'
group by o.code,o.name, a.SaveDate having sum(a.PreBal) >0  order by a.SaveDate, o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['fdue'] = $return;

$query = "select o.code,o.name, o.id,
(select count(memberid) from member where regdate between '$date1' and '$date2' and officeid=o.id and status='ACTIVE')NewM,
(select count(memberid) from member where DropOutDate between '$date1' and '$date2' and officeid=o.id and Status='DROPOUT')DropM
from  officedetail o, Member a where o.id=a.officeid  $test group by o.code, o.name, o.id  order by  o.code";          
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
}
$data['members'] = $return;


if ($data!= null){
			$response["status"] = "Success";
			$response["data"] = $data;
        } else{
			$response["status"] = "Success";
			$response["data"] = "No data";	
		}
header('Content-Type: application/json');
$endoded = json_encode($response, JSON_PRETTY_PRINT);
echo $endoded;
?>